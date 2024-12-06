@extends('layouts.app')

@section('title', 'Historique des Matchs Zobibi')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Titre avec animation et icône --}}
    <h1 class="text-3xl font-extrabold tracking-tight mb-10 
               transform transition-all duration-300 hover:scale-105 
               flex items-center justify-center">
        <i class="ri-file-list-line text-4xl mr-3 animate-pulse"></i>
        Historique des Matchs Zobibi
    </h1>

    @if($matches->isEmpty())
        {{-- Carte d'alerte améliorée --}}
        <div class="max-w-md mx-auto bg-white border border-blue-200 
                    text-gray-800 p-6 rounded-lg 
                    shadow-lg transform transition hover:scale-105">
            <div class="flex items-center mb-2">
                <i class="ri-information-line text-2xl mr-2 text-blue-600"></i>
                <p class="font-bold text-lg">Aucun match n'a été généré</p>
            </div>
            <p class="text-sm">Commencez par générer un premier match Zobibi !</p>
        </div>
    @else
        {{-- Tableau amélioré avec hover et transition --}}
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-green-700 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-16">
                                #
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-36">
                                Ville 1
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-36">
                                Région 1
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-36">
                                Ville 2
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-36">
                                Région 2
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-36">
                                Date
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($matches as $match)
                            <tr class="hover:bg-gray-50 transition-all duration-200 ease-in-out 
                                       hover:shadow-lg cursor-pointer">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                        {{ $match->id }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <span class="font-semibold text-green-700">{{ $match->city1->name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span class="bg-gray-100 px-2 py-1 rounded-md">
                                        {{ $match->city1->region->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <span class="font-semibold text-green-700">{{ $match->city2->name }}</span>
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
                <div class="text-gray-600 text-sm bg-green-50 px-4 py-2 rounded-md">
                    Affichage de 
                    <span class="font-bold text-green-700">{{ $matches->firstItem() }}</span> 
                    à 
                    <span class="font-bold text-green-700">{{ $matches->lastItem() }}</span> 
                    sur 
                    <span class="font-bold text-green-700">{{ $matches->total() }}</span> 
                    matchs
                </div>
                {{ $matches->links('vendor.pagination.tailwind') }}
            </div>
        </div>

        {{-- Bouton de réinitialisation --}}
        <div class="mt-6 text-center">
            <form action="{{ route('reinitialiser-matchs') }}" method="POST" 
                  onsubmit="return confirm('Voulez-vous vraiment réinitialiser tous les matchs ?');">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded 
                                             inline-flex items-center 
                                             transition duration-300 transform hover:scale-105">
                    <i class="ri-restart-line mr-2"></i>
                    Réinitialiser les matchs
                </button>
            </form>
        </div>
    @endif
</div>
@endsection