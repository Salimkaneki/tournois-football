@extends('layouts.app')

@section('title', 'Statistiques')

@section('content')
<div class="max-w-md mx-auto bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">📊 Statistiques du Tournoi</h2>
    
    <p class="text-gray-600 mb-6 text-center">Visualisez ici les statistiques des matchs joués.</p>
    
    <div class="bg-gray-100 rounded-lg p-4">
        <ul class="space-y-2">
            <li class="flex justify-between border-b pb-2 last:border-b-0">
                <span class="font-medium text-gray-700">Nombre total de matchs</span>
                <span class="font-bold text-blue-600">10</span>
            </li>
            <li class="flex justify-between border-b pb-2 last:border-b-0">
                <span class="font-medium text-gray-700">Matchs Kpessekou</span>
                <span class="font-bold text-green-600">6</span>
            </li>
            <li class="flex justify-between">
                <span class="font-medium text-gray-700">Matchs Zobibi</span>
                <span class="font-bold text-red-600">4</span>
            </li>
        </ul>
    </div>
</div>
@endsection