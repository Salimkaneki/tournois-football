@extends('layouts.app')

@section('title', 'Réinitialiser le Tournoi')

@section('content')
<div class="max-w-md mx-auto bg-white shadow-lg rounded-lg p-6 text-center">
    <h2 class="text-2xl font-bold text-red-600 mb-4 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        ⚠️ Réinitialiser le Tournoi
    </h2>
    
    <p class="text-gray-600 mb-6">Cette action supprimera toutes les données existantes.</p>
    
    <button class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg shadow-md transition-all flex items-center justify-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        <span>Confirmer la Réinitialisation</span>
    </button>
</div>
@endsection