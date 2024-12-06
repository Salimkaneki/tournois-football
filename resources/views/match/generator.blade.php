@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Générateur de Match</h1>
    <button id="genererBtn" class="btn btn-primary">Générer un Match</button>
    
    <div id="resultat" class="mt-3">
        <!-- Résultat s'affichera ici -->
    </div>
</div>

@push('scripts')
<script>
document.getElementById('genererBtn').addEventListener('click', function() {
    fetch('{{ route("match.generer") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('resultat').innerHTML = `
            Match généré : ${data.ville1} vs ${data.ville2}
        `;
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
});
</script>
@endpush