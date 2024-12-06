@extends('layouts.app')

@section('title', 'Historique des Matchs Kpessekou')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Titre avec animation et icône dynamique --}}
    <h1 class="text-4xl font-extrabold text-center mb-10 text-transparent bg-clip-text 
               bg-gradient-to-r from-green-700 to-green-900 
               transform transition-all duration-300 hover:scale-105 
               flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mr-3 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
        </svg>
        Historique des Matchs Kpessekou
    </h1>

    @if($matches->isEmpty())
        {{-- Carte d'alerte améliorée --}}
        <div class="max-w-md mx-auto bg-gradient-to-r from-blue-100 to-blue-200 
                    border-l-4 border-blue-500 text-blue-800 p-6 rounded-lg 
                    shadow-2xl transform transition hover:scale-105">
            <div class="flex items-center mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="font-bold text-lg">Aucun match n'a été généré</p>
            </div>
            <p class="text-sm">Commencez par générer un premier match Kpessekou !</p>
        </div>
    @else
        {{-- Tableau amélioré avec hover et transition --}}
        <div class="bg-white shadow-2xl rounded-xl overflow-hidden border-2 border-blue-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-blue-200">
                    <thead class="bg-gradient-to-r from-green-700 to-green-900 text-white">
                        <tr>
                            @php 
                                $headers = [
                                    '#' => 'w-16', 
                                    'Ville 1' => 'w-36', 
                                    'Région 1' => 'w-36', 
                                    'Ville 2' => 'w-36', 
                                    'Région 2' => 'w-36', 
                                    'Date' => 'w-36'
                                ];
                            @endphp
                            @foreach($headers as $header => $width)
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider {{ $width }}">
                                    {{ $header }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-blue-100">
                        @foreach($matches as $match)
                            <tr class="hover:bg-blue-50 transition-all duration-200 ease-in-out 
                                       hover:shadow-lg cursor-pointer">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                        {{ $match->id }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <span class="font-semibold text-blue-700">{{ $match->city1->name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span class="bg-gray-100 px-2 py-1 rounded-md">
                                        {{ $match->city1->region->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <span class="font-semibold text-blue-700">{{ $match->city2->name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span class="bg-gray-100 px-2 py-1 rounded-md">
                                        {{ $match->city2->region->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                        {{ $match->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination améliorée --}}
        <div class="mt-8">
            <div class="flex justify-between items-center">
                <div class="text-gray-600 text-sm bg-blue-50 px-4 py-2 rounded-md">
                    Affichage de 
                    <span class="font-bold text-blue-700">{{ $matches->firstItem() }}</span> 
                    à 
                    <span class="font-bold text-blue-700">{{ $matches->lastItem() }}</span> 
                    sur 
                    <span class="font-bold text-blue-700">{{ $matches->total() }}</span> 
                    matchs
                </div>
                <div>
                    {{ $matches->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection