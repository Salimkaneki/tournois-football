@extends('layouts.app')
@section('title', 'Génération de Matchs Kpessekou')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">🏆 Génération de Matchs Éliminatoires Kpessekou</h2>
        
        <div class="mb-4 text-gray-600">
            <p>Nombre total de Régions : {{ $totalRegions }}</p>
            <p>Nombre total de Villes : {{ $totalCities }}</p>
        </div>
        
        <p class="text-gray-600 mb-6">Cliquez sur le bouton ci-dessous pour générer un match entre deux villes de régions différentes.</p>
        
        <button id="generate-match-btn" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow-md transition-all">
            Générer un Match Kpessekou
        </button>
        
        <div id="result" class="mt-6 p-4 bg-gray-100 rounded-lg min-h-[100px] flex items-center justify-center">
            <p class="text-gray-500">Cliquez sur "Générer un Match Kpessekou" pour commencer</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const generateButton = document.getElementById('generate-match-btn');
        const resultDiv = document.getElementById('result');
        
        generateButton.addEventListener('click', function () {
            // Afficher l'état de chargement
            resultDiv.innerHTML = `
                <div class="flex items-center justify-center space-x-2">
                    <div class="animate-spin">⚽</div>
                    <span>Génération du match en cours...</span>
                </div>
            `;
            
            // Envoyer une requête AJAX pour générer le match
            fetch('{{ route("generate.kpessekou") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur de génération de match : ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (!data.success) {
                    resultDiv.innerHTML = `<p class="text-red-500 font-bold">${data.error}</p>`;
                } else {
                    resultDiv.innerHTML = `
                        <div>
                            <p class="font-bold text-lg mb-2">Match Éliminatoire Kpessekou</p>
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-semibold">${data.city1}</span>
                                    <span class="text-sm text-gray-500 ml-2">(${data.region1})</span>
                                </div>
                                <span class="mx-4 text-gray-500">VS</span>
                                <div>
                                    <span class="font-semibold">${data.city2}</span>
                                    <span class="text-sm text-gray-500 ml-2">(${data.region2})</span>
                                </div>
                            </div>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Erreur :', error);
                resultDiv.innerHTML = `<p class="text-red-500 font-bold">Une erreur est survenue : ${error.message}</p>`;
            });
        });
    });
</script>
@endpush