<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\City;
use App\Models\Matches;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log; 

class KpessekouController extends Controller
{
    public function index()
    {
        // Log d'accès à la page d'index
        Log::info('Accessing Kpessekou index page');
        return view('kpessekou');
    }

    public function generateMatch()
    {
        try {
            // Log de début de génération de match
            Log::info('Starting match generation process');

            DB::beginTransaction();

            // Vérification détaillée des régions et villes
            $regionsCount = Region::count();
            $citiesCount = City::count();
            
            Log::info('Database state before match generation', [
                'total_regions' => $regionsCount,
                'total_cities' => $citiesCount
            ]);

            // Vérifier s'il y a suffisamment de régions et de villes
            if ($regionsCount < 2 || $citiesCount < 2) {
                Log::warning('Insufficient regions or cities for match generation');
                return response()->json([
                    'error' => 'Pas assez de régions ou de villes pour générer un match.'
                ], 400);
            }

            // Récupérer des régions différentes
            $regions = Region::has('cities', '>', 1)->get();

            if ($regions->count() < 2) {
                Log::warning('Not enough regions with multiple cities');
                return response()->json([
                    'error' => 'Impossible de trouver des régions avec plusieurs villes.'
                ], 400);
            }

            // Sélectionner deux régions différentes aléatoirement
            $randomRegions = $regions->random(2);

            Log::info('Selected regions for match', [
                'region1' => $randomRegions[0]->name,
                'region2' => $randomRegions[1]->name
            ]);

            // Sélectionner une ville aléatoire dans chaque région
            $city1 = City::where('region_id', $randomRegions[0]->id)
                ->inRandomOrder()
                ->first();

            $city2 = City::where('region_id', $randomRegions[1]->id)
                ->inRandomOrder()
                ->first();

            Log::info('Selected cities for match', [
                'city1' => $city1->name,
                'region1' => $city1->region->name,
                'city2' => $city2->name,
                'region2' => $city2->region->name
            ]);

            // Vérifier les matchs précédents
            $existingMatch = Matches::where(function($query) use ($city1, $city2) {
                $query->where('city1_id', $city1->id)
                      ->where('city2_id', $city2->id);
            })->orWhere(function($query) use ($city1, $city2) {
                $query->where('city1_id', $city2->id)
                      ->where('city2_id', $city1->id);
            })->first();

            if ($existingMatch) {
                Log::warning('Match already exists between these cities');
                return response()->json([
                    'error' => 'Ce match a déjà été généré précédemment.'
                ], 400);
            }

            // Créer le match
            $match = Matches::create([
                'city1_id' => $city1->id,
                'city2_id' => $city2->id,
                'type' => 'Kpessekou',
                'match_date' => now()
            ]);

            // Mettre à jour les statistiques
            $city1->increment('hosting_count');
            $city2->increment('hosting_count');
            $city1->update(['has_hosted' => true]);
            $city2->update(['has_hosted' => true]);

            DB::commit();

            Log::info('Match generated successfully');

            return response()->json([
                'city1' => $city1->name,
                'region1' => $city1->region->name,
                'city2' => $city2->name,
                'region2' => $city2->region->name
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Match Generation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Une erreur est survenue : ' . $e->getMessage()
            ], 500);
        }
    }
}