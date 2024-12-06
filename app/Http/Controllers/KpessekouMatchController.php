<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\City;
use App\Models\Matches; // Renamed from Matches to Match for clarity
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class KpessekouMatchController extends Controller
{
    /**
     * Display the Kpessekou match generation page
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get total number of cities and regions for context
        $totalCities = City::count();
        $totalRegions = Region::count();

        return view('kpessekou', compact('totalCities', 'totalRegions'));
    }

    /**
     * Generate a Kpessekou match between two cities from different regions
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateMatch()
    {
        try {
            // Start a database transaction
            DB::beginTransaction();

            // Get cities that haven't hosted a match yet
            $unusedCities = City::whereDoesntHave('matches')->get();

            // If all cities have been used, reset the match history
            if ($unusedCities->isEmpty()) {
                // Delete all previous matches
                Matches::truncate();
                // Reset with all cities
                $unusedCities = City::all();
            }

            // Get regions with unused cities
            $availableRegions = Region::whereHas('cities', function($query) use ($unusedCities) {
                $query->whereIn('id', $unusedCities->pluck('id'));
            })->get();

            // Select two cities from different regions
            $selectedCities = $this->selectCitiesDifferentRegions($availableRegions, $unusedCities);

            // Create a new match
            $match = Matches::create([
                'city1_id' => $selectedCities['city1']->id,
                'city2_id' => $selectedCities['city2']->id,
                'match_date' => now(),
                'type' => 'Kpessekou'
            ]);

            // Commit the transaction
            DB::commit();

            // Return match details
            return response()->json([
                'success' => true,
                'city1' => $selectedCities['city1']->name,
                'region1' => $selectedCities['city1']->region->name,
                'city2' => $selectedCities['city2']->name,
                'region2' => $selectedCities['city2']->region->name
            ]);

        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            // Log the error
            Log::error('Match generation error: ' . $e->getMessage());

            // Return error response
            return response()->json([
                'success' => false,
                'error' => 'Unable to generate a match. Please try again.'
            ], 500);
        }
    }

    

    /**
     * Select two cities from different regions
     * 
     * @param \Illuminate\Support\Collection $availableRegions
     * @param \Illuminate\Support\Collection $unusedCities
     * @return array
     * @throws \Exception
     */
    private function selectCitiesDifferentRegions($availableRegions, $unusedCities)
    {
        $shuffledRegions = $availableRegions->shuffle();
    
        foreach ($shuffledRegions as $region1) {
            $region1Cities = $unusedCities->where('region_id', $region1->id);
    
            foreach ($shuffledRegions as $region2) {
                if ($region1->id === $region2->id) {
                    continue;
                }
    
                $region2Cities = $unusedCities->where('region_id', $region2->id);
    
                if ($region1Cities->isNotEmpty() && $region2Cities->isNotEmpty()) {
                    return [
                        'city1' => $region1Cities->random(),
                        'city2' => $region2Cities->random(),
                    ];
                }
            }
        }
    
        // Retournez un tableau vide au lieu de lancer une exception
        return null;
    }
    

    /**
     * Get match history
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function matchHistory()
    {
        $matches = Matches::with(['city1', 'city2'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'matches' => $matches,
            'total_matches' => $matches->total()
        ]);
    }
}
