<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\City;
use App\Models\Matches;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class MatchZobibiController extends Controller
{
    public function index()
    {
        // Récupérer toutes les régions
        $regions = Region::all();
        
        // Log pour le débogage
        Log::info('Initialisation de la page Zobibi', [
            'regions_count' => $regions->count(),
            'cities_count' => City::count()
        ]);

        return view('zobibi', compact('regions'));
    }

    public function genererMatch(Request $request)
    {
        try {
            // Validation robuste des régions
            $request->validate([
                'region1' => [
                    'required', 
                    'exists:regions,name',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value === $request->region2) {
                            $fail('Les deux régions doivent être différentes.');
                        }
                    }
                ],
                'region2' => [
                    'required', 
                    'exists:regions,name',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value === $request->region1) {
                            $fail('Les deux régions doivent être différentes.');
                        }
                    }
                ]
            ]);

            // Vérifier que les tables ne sont pas vides
            if (Region::count() == 0 || City::count() == 0) {
                throw new \Exception('Aucune région ou ville trouvée en base de données');
            }

            DB::beginTransaction();

            // Trouver les régions
            $region1 = Region::where('name', $request->region1)->firstOrFail();
            $region2 = Region::where('name', $request->region2)->firstOrFail();

            // Trouver les villes qui n'ont pas encore été utilisées dans un match
            $usedCityIds = Matches::pluck('city1_id')->merge(Matches::pluck('city2_id'));

            // Villes de chaque région non utilisées
            $region1Cities = City::where('region_id', $region1->id)
                ->whereNotIn('id', $usedCityIds)
                ->get();

            $region2Cities = City::where('region_id', $region2->id)
                ->whereNotIn('id', $usedCityIds)
                ->get();

            // Si toutes les villes d'une région ont été utilisées, réinitialiser cette région
            if ($region1Cities->isEmpty()) {
                $region1Cities = City::where('region_id', $region1->id)->get();
            }

            if ($region2Cities->isEmpty()) {
                $region2Cities = City::where('region_id', $region2->id)->get();
            }

            // Vérifier qu'il y a des villes disponibles
            if ($region1Cities->isEmpty() || $region2Cities->isEmpty()) {
                throw new \Exception('Pas de villes disponibles dans l\'une des régions');
            }

            // Sélectionner une ville au hasard dans chaque région
            $ville1 = $region1Cities->random();
            $ville2 = $region2Cities->random();

            // Créer le match
            $match = Matches::create([
                'city1_id' => $ville1->id,
                'city2_id' => $ville2->id
            ]);

            DB::commit();

            // Logging pour le débogage
            Log::info('Match Zobibi généré avec succès', [
                'ville1' => $ville1->name,
                'region1' => $region1->name,
                'ville2' => $ville2->name,
                'region2' => $region2->name
            ]);

            return response()->json([
                'ville1' => $ville1->name,
                'region1' => $region1->name,
                'ville2' => $ville2->name,
                'region2' => $region2->name
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Erreur de validation', [
                'errors' => $e->errors()
            ]);

            return response()->json([
                'error' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur de génération de match', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function historique()
    {
        // Récupérer les matchs avec leurs villes et régions, par ordre décroissant
        $matches = Matches::with(['city1.region', 'city2.region'])
            ->latest()
            ->paginate(10); // 10 matchs par page

        return view('zobibi-historique', compact('matches'));
    }

    // Méthode pour réinitialiser les matchs
    public function reinitialiserMatchs()
    {
        Matches::truncate(); // Supprime tous les matchs
        
        return redirect()->route('zobibi-historique')
            ->with('success', 'Tous les matchs ont été réinitialisés.');
    }
}