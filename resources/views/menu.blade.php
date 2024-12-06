@extends('layouts.app')

@section('title', 'Menu Principal')

@section('content')
<div class="min-h-[calc(100vh-200px)] flex flex-col justify-center items-center space-y-6 p-4">
    <h2 class="text-4xl font-extrabold text-gray-800 mb-6 text-center">
        Bienvenue dans le tournoi 
        <span class="text-green-600">🇹🇬</span>
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 w-full max-w-5xl">
        {{-- Kpessekou Tirage --}}
        <div class="group">
            <a href="{{ route('kpessekou.index') }}" class="block">
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-xl shadow-2xl transform transition-all duration-300 hover:scale-105 hover:shadow-blue-500/50 p-6 text-center">
                    <div class="flex flex-col items-center space-y-4">
                        <div class="bg-white/20 rounded-full p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.768-.231-1.481-.634-2.117M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.768.231-1.481.634-2.117M14 10h4.464c.705 0 1.136.754.811 1.372l-.43.744a2 2 0 01-1.762 1.025 2 2 0 00-1.406.586 2 2 0 01-2.828 0 2 2 0 00-1.406-.586h0a2 2 0 01-1.762-1.025l-.43-.744c-.325-.618.106-1.372.811-1.372H10m4 0V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v5h8zM14 10h-4v5h4v-5z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Tirage Kpessekou</h3>
                            <p class="text-sm text-white/80">Découvrez les matchs</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Zobibi Tirage --}}
        <div class="group">
            <a href="{{ route('zobibi.index') }}" class="block">
                <div class="bg-gradient-to-br from-green-500 to-green-700 text-white rounded-xl shadow-2xl transform transition-all duration-300 hover:scale-105 hover:shadow-green-500/50 p-6 text-center">
                    <div class="flex flex-col items-center space-y-4">
                        <div class="bg-white/20 rounded-full p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Tirage Zobibi</h3>
                            <p class="text-sm text-white/80">Explorez les matchs</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Historique Kpessekou --}}
        <div class="group">
            <a href="{{ route('kpessekou.history') }}" class="block">
                <div class="bg-gradient-to-br from-teal-500 to-teal-700 text-white rounded-xl shadow-2xl transform transition-all duration-300 hover:scale-105 hover:shadow-teal-500/50 p-6 text-center">
                    <div class="flex flex-col items-center space-y-4">
                        <div class="bg-white/20 rounded-full p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Historique Kpessekou</h3>
                            <p class="text-sm text-white/80">Revivez les matchs</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Historique Zobibi --}}
        <div class="group">
            <a href="{{ route('zobibi-historique') }}" class="block">
                <div class="bg-gradient-to-br from-red-500 to-red-700 text-white rounded-xl shadow-2xl transform transition-all duration-300 hover:scale-105 hover:shadow-red-500/50 p-6 text-center">
                    <div class="flex flex-col items-center space-y-4">
                        <div class="bg-white/20 rounded-full p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Historique Zobibi</h3>
                            <p class="text-sm text-white/80">Consultez les résultats</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection