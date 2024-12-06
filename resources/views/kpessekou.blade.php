@extends('layouts.app')

@section('title', 'Kpessekou Match Generation')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">🏆 Kpessekou Playoff Match Generator</h2>
        
        <div class="mb-4 text-gray-600">
            <p>Total Regions: {{ $totalRegions }}</p>
            <p>Total Cities: {{ $totalCities }}</p>
        </div>
        
        <p class="text-gray-600 mb-6">Click the button below to generate a match between two cities from different regions.</p>
        
        <button id="generate-match-btn" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow-md transition-all">
            Generate Kpessekou Match
        </button>
        
        <div id="result" class="mt-6 p-4 bg-gray-100 rounded-lg min-h-[100px] flex items-center justify-center">
            <p class="text-gray-500">Click "Generate Kpessekou Match" to begin</p>
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
            // Show loading state
            resultDiv.innerHTML = `
                <div class="flex items-center justify-center space-x-2">
                    <div class="animate-spin">⚽</div>
                    <span>Generating match...</span>
                </div>
            `;

            // Send AJAX request to generate match
            fetch('{{ route("generate.kpessekou") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Match generation error: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (!data.success) {
                    resultDiv.innerHTML = `<p class="text-red-500 font-bold">${data.error}</p>`;
                } else {
                    resultDiv.innerHTML = `
                        <div>
                            <p class="font-bold text-lg mb-2">Kpessekou Playoff Match</p>
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
                console.error('Error:', error);
                resultDiv.innerHTML = `<p class="text-red-500 font-bold">An error occurred: ${error.message}</p>`;
            });
        });
    });
</script>
@endpush