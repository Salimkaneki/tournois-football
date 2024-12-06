<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MatchGeneratorController extends Controller
{
    public function index()
    {
        // Liste des villes et régions si nécessaire
        $villes = ['Abidjan', 'Bouaké', 'Yamoussoukro', 'San-Pédro', 'Daloa'];
        $regions = ['Sud', 'Nord', 'Centre', 'Est', 'Ouest'];

        return view('match.generator', compact('villes', 'regions'));
    }

    public function genererMatch()
    {
        $villes = ['Abidjan', 'Bouaké', 'Yamoussoukro', 'San-Pédro', 'Daloa'];
        $regions = ['Sud', 'Nord', 'Centre', 'Est', 'Ouest'];

        // Sélection aléatoire de deux villes de régions différentes
        $ville1 = $villes[array_rand($villes)];
        do {
            $ville2 = $villes[array_rand($villes)];
        } while ($ville2 == $ville1);

        return response()->json([
            'ville1' => $ville1,
            'ville2' => $ville2
        ]);
    }
}