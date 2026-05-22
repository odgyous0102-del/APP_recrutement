@extends('layouts.app')

@section('title', 'Statistiques de la Plateforme - Admin')

@section('content')
<div class="flex h-screen bg-[#f8fafc] overflow-hidden">
    @include('admin.partials.sidebar', ['active' => 'statistics'])

    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Barre supérieure --}}
        @include('admin.partials.header', ['title' => 'Statistiques Détaillées'])

        <main class="flex-1 overflow-y-auto p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                {{-- Répartition des Offres --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="text-base font-bold text-gray-500 uppercase tracking-widest mb-6">Offres par Statut</h3>
                    <div class="space-y-4">
                        @foreach($offresParStatut as $item)
                        <div>
                            <div class="flex justify-between text-base mb-1">
                                <span class="font-medium text-gray-700 capitalize">{{ $item->statut }}</span>
                                <span class="font-bold text-indigo-600">{{ $item->count }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $item->count > 0 ? ($item->count / $offresParStatut->sum('count')) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Répartition des Candidatures --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="text-base font-bold text-gray-500 uppercase tracking-widest mb-6">Candidatures par Statut</h3>
                    <div class="space-y-4">
                        @foreach($candidaturesParStatut as $item)
                        <div>
                            <div class="flex justify-between text-base mb-1">
                                <span class="font-medium text-gray-700 capitalize">{{ $item->statut }}</span>
                                <span class="font-bold text-purple-600">{{ $item->count }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $item->count > 0 ? ($item->count / $candidaturesParStatut->sum('count')) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Top Entreprises --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50">
                    <h3 class="text-base font-bold text-gray-500 uppercase tracking-widest">Top 10 Entreprises les plus actives</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold tracking-widest">
                            <tr>
                                <th class="px-8 py-4">Entreprise</th>
                                <th class="px-8 py-4 text-center">Offres Publiées</th>
                                <th class="px-8 py-4">Progression</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($topEntreprises as $entreprise)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-8 py-5">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 font-bold mr-4">
                                            {{ substr($entreprise->nom_entreprise, 0, 1) }}
                                        </div>
                                        <span class="font-bold text-gray-900">{{ $entreprise->nom_entreprise }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center font-bold text-gray-700">
                                    {{ $entreprise->offres_count }}
                                </td>
                                <td class="px-8 py-5">
                                    <div class="w-48 bg-gray-100 rounded-full h-1.5">
                                        <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ ($entreprise->offres_count / ($topEntreprises->first()->offres_count ?: 1)) * 100 }}%"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
