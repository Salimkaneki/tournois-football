@extends('layouts.app')

@section('title', 'Match Zobibi')

@section('content')
<div class="max-w-md mx-auto bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">🎲 Génération d'un match Zobibi</h2>
    
    <p class="text-gray-600 mb-6 text-center">Choisissez deux régions différentes pour générer un match.</p>
    
    <form id="zobibi-form" class="space-y-4">
        @csrf
        <div>
            <label for="region1" class="block text-sm font-medium text-gray-700 mb-2">Région 1</label>
            <select id="region1" name="region1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">Sélectionnez une région</option>
                @foreach($regions as $region)
                    <option value="{{ $region->name }}">{{ $region->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label for="region2" class="block text-sm font-medium text-gray-700 mb-2">Région 2</label>
            <select id="region2" name="region2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">Sélectionnez une région</option>
                @foreach($regions as $region)
                    <option value="{{ $region->name }}">{{ $region->name }}</option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg shadow-md transition-all flex items-center justify-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Générer un Match</span>
        </button>
    </form>
    
    <div id="resultat" class="mt-6 p-4 bg-gray-100 rounded-lg min-h-[100px] text-center flex items-center justify-center">
        <!-- Résultat du match sera inséré ici -->
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('zobibi-form');
    const resultDiv = document.getElementById('resultat');
    const region1Select = document.getElementById('region1');
    const region2Select = document.getElementById('region2');

    // Validation des régions différentes
    region1Select.addEventListener('change', function() {
        // Désactiver l'option sélectionnée dans l'autre select
        Array.from(region2Select.options).forEach(option => {
            option.disabled = (option.value === this.value);
        });
    });

    region2Select.addEventListener('change', function() {
        // Désactiver l'option sélectionnée dans l'autre select
        Array.from(region1Select.options).forEach(option => {
            option.disabled = (option.value === this.value);
        });
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validation finale
        if (!region1Select.value || !region2Select.value) {
            alert('Veuillez sélectionner deux régions différentes');
            return;
        }

        // Désactiver le bouton pendant le chargement
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Génération en cours...
        `;

        const formData = new FormData(form);

        fetch('{{ route('generer-match-zobibi') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token')
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.error || 'Erreur de génération du match');
                });
            }
            return response.json();
        })
        .then(data => {
            resultDiv.innerHTML = `
                <div class="text-center">
                    <p class="font-bold text-xl mb-2">Match Zobibi Généré ✨</p>
                    <p class="text-lg">
                        🏙️ ${data.ville1} (${data.region1}) 
                        <span class="mx-2 text-gray-500">VS</span> 
                        ${data.ville2} (${data.region2})
                    </p>
                </div>
            `;
        })
        .catch(error => {
            console.error('Erreur:', error);
            resultDiv.innerHTML = `
                <div class="text-center text-red-500">
                    <p>Erreur lors de la génération du match</p>
                    <p class="text-sm">${error.message}</p>
                </div>
            `;
        })
        .finally(() => {
            // Réactiver le bouton
            submitButton.disabled = false;
            submitButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Générer un Match</span>
            `;
        });
    });
});
</script>
@endpush
@endsection