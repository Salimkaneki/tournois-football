<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\City;
use App\Models\Matches;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatchZobibiController extends Controller
{
    public function index()
    {
        $totalCities = City::count();
        $totalRegions = Region::count();
        $regions = Region::all();

        // Log des informations de base
        Log::info('Page Zobibi initialisée', [
            'total_cities' => $totalCities,
            'total_regions' => $totalRegions,
            'regions' => $regions->pluck('name')
        ]);

        return view('zobibi', compact('regions', 'totalCities', 'totalRegions'));
    }

    public function genererMatch(Request $request)
    {
        // Log des données de la requête entrante
        Log::info('Tentative de génération de match Zobibi', [
            'regions_input' => $request->only(['region1', 'region2'])
        ]);

        try {
            // Validation des régions
            $validatedData = $request->validate([
                'region1' => 'required|different:region2',
                'region2' => 'required'
            ]);

            // Log après validation
            Log::info('Régions validées', $validatedData);

            DB::beginTransaction();

            // Trouver les régions
            $region1 = Region::where('name', $request->region1)->first();
            $region2 = Region::where('name', $request->region2)->first();

            // Log des régions trouvées
            Log::info('Régions trouvées', [
                'region1' => $region1 ? $region1->toArray() : 'Non trouvée',
                'region2' => $region2 ? $region2->toArray() : 'Non trouvée'
            ]);

            // Villes qui n'ont pas encore hébergé de match Zobibi
            $unusedCities = City::whereDoesntHave('matches', function($query) {
                $query->where('type', 'Zobibi');
            })->get();

            // Log des villes non utilisées
            Log::info('Villes non utilisées', [
                'count' => $unusedCities->count(),
                'city_ids' => $unusedCities->pluck('id'),
                'city_names' => $unusedCities->pluck('name')
            ]);

            // Si toutes les villes ont été utilisées, réinitialiser
            if ($unusedCities->isEmpty()) {
                Log::warning('Toutes les villes ont été utilisées. Réinitialisation en cours.');

                // Supprimer tous les matchs Zobibi précédents
                Matches::where('type', 'Zobibi')->delete();
                
                // Réinitialiser avec toutes les villes
                $unusedCities = City::all();
                
                // Réinitialiser le compteur d'hébergement
                City::query()->update([
                    'has_hosted' => false, 
                    'hosting_count' => 0
                ]);
            }

            // Sélectionner les villes
            $selectedCities = $this->selectCitiesDifferentRegions(
                collect([$region1, $region2]), 
                $unusedCities
            );

            // Log de la sélection des villes
            Log::info('Villes sélectionnées', [
                'city1' => $selectedCities['city1'] ? $selectedCities['city1']->toArray() : 'Aucune',
                'city2' => $selectedCities['city2'] ? $selectedCities['city2']->toArray() : 'Aucune'
            ]);

            if (!$selectedCities) {
                throw new \Exception('Impossible de trouver des villes correspondantes');
            }

            // Créer un nouvel enregistrement de match
            $match = Matches::create([
                'city1_id' => $selectedCities['city1']->id,
                'city2_id' => $selectedCities['city2']->id,
                'type' => 'Zobibi',
                'match_date' => now()
            ]);

            // Log de création du match
            Log::info('Match Zobibi créé', [
                'match_id' => $match->id,
                'city1_id' => $match->city1_id,
                'city2_id' => $match->city2_id
            ]);

            // Mettre à jour le statut d'hébergement des villes
            $selectedCities['city1']->increment('hosting_count');
            $selectedCities['city1']->update(['has_hosted' => true]);
            $selectedCities['city2']->increment('hosting_count');
            $selectedCities['city2']->update(['has_hosted' => true]);

            DB::commit();

            // Log de succès
            Log::info('Match Zobibi généré avec succès', [
                'ville1' => $selectedCities['city1']->name,
                'region1' => $selectedCities['city1']->region->name,
                'ville2' => $selectedCities['city2']->name,
                'region2' => $selectedCities['city2']->region->name
            ]);

            return response()->json([
                'success' => true,
                'ville1' => $selectedCities['city1']->name,
                'region1' => $selectedCities['city1']->region->name,
                'ville2' => $selectedCities['city2']->name,
                'region2' => $selectedCities['city2']->region->name
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            // Log détaillé de l'erreur
            Log::error('Erreur de génération de match Zobibi', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input_regions' => $request->only(['region1', 'region2'])
            ]);

            return response()->json([
                'success' => false,
                'erreur' => 'Impossible de générer le match. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sélectionner des villes de régions différentes
     */
    private function selectCitiesDifferentRegions($regions, $unusedCities)
    {
        // Log du début de la sélection
        Log::info('Début de sélection des villes', [
            'regions_ids' => $regions->pluck('id'),
            'unused_cities_count' => $unusedCities->count()
        ]);

        $regions = $regions->shuffle();

        foreach ($regions as $region1) {
            $region1Cities = $unusedCities->where('region_id', $region1->id);

            foreach ($regions as $region2) {
                if ($region1->id === $region2->id) {
                    continue;
                }

                $region2Cities = $unusedCities->where('region_id', $region2->id);

                // Log de la recherche
                Log::info('Recherche de villes', [
                    'region1_id' => $region1->id,
                    'region2_id' => $region2->id,
                    'region1_cities_count' => $region1Cities->count(),
                    'region2_cities_count' => $region2Cities->count()
                ]);

                if ($region1Cities->isNotEmpty() && $region2Cities->isNotEmpty()) {
                    return [
                        'city1' => $region1Cities->random(),
                        'city2' => $region2Cities->random(),
                    ];
                }
            }
        }

        // Log si aucune ville n'est trouvée
        Log::warning('Aucune ville trouvée pour les régions données');

        return null;
    }

    /**
     * Historique des matchs Zobibi
     */
    public function historique()
    {
        $matches = Matches::where('type', 'Zobibi')
            ->with(['city1', 'city2'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'matches' => $matches,
            'total_matches' => $matches->total()
        ]);
    }
}