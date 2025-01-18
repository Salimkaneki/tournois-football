<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\City;
use App\Models\Matches;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatchZobibiController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        return view('zobibi', compact('regions'));
    }

    public function genererMatch(Request $request)
    {
        try {
            // 1. Validation
            $request->validate([
                'region1' => ['required', 'exists:regions,name'],
                'region2' => [
                    'required', 
                    'exists:regions,name',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value === $request->region1) {
                            $fail('Les régions doivent être différentes.');
                        }
                    }
                ]
            ]);

            // 2. Récupération des régions
            $region1 = Region::where('name', $request->region1)->first();
            $region2 = Region::where('name', $request->region2)->first();

            // 3. Sélection des villes
            $usedCityIds = Matches::pluck('city1_id')->merge(Matches::pluck('city2_id'));
            
            $ville1 = $this->getRandomCity($region1->id, $usedCityIds);
            $ville2 = $this->getRandomCity($region2->id, $usedCityIds);

            // 4. Création du match
            Matches::create([
                'city1_id' => $ville1->id,
                'city2_id' => $ville2->id
            ]);

            return response()->json([
                'ville1' => $ville1->name,
                'region1' => $region1->name,
                'ville2' => $ville2->name,
                'region2' => $region2->name
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function getRandomCity($regionId, $usedCityIds)
    {
        $ville = City::where('region_id', $regionId)
            ->whereNotIn('id', $usedCityIds)
            ->get();

        // Si toutes les villes sont utilisées, on réinitialise
        if ($ville->isEmpty()) {
            $ville = City::where('region_id', $regionId)->get();
        }

        return $ville->random();
    }

    public function historique()
    {
        $matches = Matches::with(['city1.region', 'city2.region'])
            ->latest()
            ->paginate(10);

        return view('zobibi-historique', compact('matches'));
    }

    public function reinitialiserMatchs()
    {
        Matches::truncate(); // Supprime tous les matchs
        
        return redirect()->route('zobibi-historique')
            ->with('success', 'Tous les matchs ont été réinitialisés.');
    }
}