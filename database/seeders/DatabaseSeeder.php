<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\City;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $regions = [
            'Région des Plateaux' => ['Atakpamé', 'Notsé', 'Kpalimé', 'Vogan'],
            'Région de Kara' => ['Kara', 'Pagouda', 'Niamtougou', 'Kandé'],
            'Région Maritime' => ['Lomé', 'Tabligbo', 'Tsévié', 'Aného'],
            'Région des Savanes' => ['Dapaong', 'Mango', 'Diapologou', 'Tône'],
            'Région Centrale' => ['Sokodé', 'Tchamba', 'Sotouboua', 'Blitta']
        ];

        foreach ($regions as $regionName => $cities) {
            $region = Region::create(['name' => $regionName]);
            
            foreach ($cities as $cityName) {
                City::create([
                    'name' => $cityName,
                    'region_id' => $region->id,
                    'has_hosted' => false,
                    'hosting_count' => 0
                ]);
            }
        }
    }
}