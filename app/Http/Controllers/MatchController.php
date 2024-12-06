<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Region;
use App\Models\Matches;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatchController extends Controller
{
    public function generateMatch()
    {
        try {
            // Log de début de génération de match
            Log::info('Starting match generation process');

            // Démarrer une transaction pour garantir l'intégrité des données
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

            // Récupérer des régions avec au moins 2 villes
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

            // Vérifier si un match existe déjà entre ces deux villes
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

            // Créer le match dans la base de données
            $match = Matches::create([
                'city1_id' => $city1->id,
                'city2_id' => $city2->id,
                'type' => 'Kpessekou',
                'match_date' => now()
            ]);

            // Mettre à jour les statistiques des villes
            $city1->increment('hosting_count');
            $city2->increment('hosting_count');
            $city1->update(['has_hosted' => true]);
            $city2->update(['has_hosted' => true]);

            // Commit de la transaction
            DB::commit();

            Log::info('Match generated successfully');

            // Retourner les résultats du match généré
            return response()->json([
                'city1' => $city1->name,
                'region1' => $city1->region->name,
                'city2' => $city2->name,
                'region2' => $city2->region->name
            ]);
        } catch (\Exception $e) {
            // Rollback de la transaction en cas d'erreur
            DB::rollBack();

            // Log de l'erreur
            Log::error('Match Generation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Retourner une erreur au client
            return response()->json([
                'error' => 'Une erreur est survenue lors de la génération du match : ' . $e->getMessage()
            ], 500);
        }
    }
}
