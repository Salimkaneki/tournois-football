<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\City;
use App\Models\Matches;
use Illuminate\Support\Facades\DB;

class KpessekouMatchController extends Controller
{
    public function index()
    {
        return view('kpessekou', [
            'totalCities' => City::count(),
            'totalRegions' => Region::count()
        ]);
    }

    public function generateMatch()
    {
        try {
            DB::beginTransaction();
            
            $match = $this->createMatch($this->selectCities());
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'city1' => $match->city1->name,
                'region1' => $match->city1->region->name,
                'city2' => $match->city2->name,
                'region2' => $match->city2->region->name
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    private function selectCities()
    {
        $unusedCities = City::whereDoesntHave('matches')->get();
        
        if ($unusedCities->isEmpty()) {
            Matches::truncate();
            $unusedCities = City::all();
        }

        $city1 = $unusedCities->random();
        $city2 = $unusedCities
            ->where('region_id', '!=', $city1->region_id)
            ->random();

        return compact('city1', 'city2');
    }

    private function createMatch($cities)
    {
        return Matches::create([
            'city1_id' => $cities['city1']->id,
            'city2_id' => $cities['city2']->id,
            'match_date' => now(),
            'type' => 'Kpessekou'
        ]);
    }

    public function matchHistory()
    {
        $matches = Matches::with(['city1.region', 'city2.region'])
            ->where('type', 'Kpessekou')
            ->latest()
            ->paginate(10);
    
        return view('kpessekou-historique', compact('matches'));
    }
}