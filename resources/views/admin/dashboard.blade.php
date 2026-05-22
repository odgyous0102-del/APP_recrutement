@extends('layouts.app')

@section('title', 'Tableau de Bord Administrateur - RecruitPro')

@section('content')
<div class="flex h-screen bg-[#f8fafc] overflow-hidden">
    {{-- Barre latérale --}}
    @include('admin.partials.sidebar', ['active' => 'dashboard'])

    {{-- Contenu principal --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Barre supérieure --}}
        @include('admin.partials.header', ['title' => 'Vue d\'ensemble Plateforme'])

        {{-- Scrollable content --}}
        <main class="flex-1 overflow-y-auto p-10 space-y-10 animate__animated animate__fadeIn">
            {{-- Welcome Section --}}
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-[2.5rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-indigo-100">
                <div class="relative z-10">
                    <h2 class="text-4xl font-black mb-2">Bonjour, {{ explode(' ', Auth::user()->name)[0] }} !</h2>
                    <p class="text-indigo-100 text-lg font-medium max-w-xl">Voici ce qui s'est passé sur votre plateforme au cours des dernières 24 heures. Vous avez {{ $stats['recruteurs_en_attente'] }} nouvelles demandes de vérification.</p>
                    <div class="mt-8 flex space-x-4">
                        <a href="{{ route('admin.recruteurs.verify') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-2xl font-bold hover:bg-indigo-50 transition shadow-lg">
                            Voir les demandes
                        </a>
                        <a href="{{ route('admin.logs') }}" class="bg-indigo-500/30 backdrop-blur-md text-white border border-white/20 px-8 py-3 rounded-2xl font-bold hover:bg-indigo-500/50 transition">
                            Journal d'audit
                        </a>
                    </div>
                </div>
                {{-- Decorative circles --}}
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-indigo-400/20 rounded-full blur-2xl"></div>
            </div>

            {{-- Statistiques --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                        <span class="text-xs font-black text-green-500 bg-green-50 px-2 py-1 rounded-lg">+12%</span>
                    </div>
                    <p class="text-sm font-black text-gray-400 uppercase tracking-widest mb-1">Total Utilisateurs</p>
                    <h3 class="text-4xl font-black text-gray-900">{{ number_format($stats['total_users']) }}</h3>
                </div>

                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-building text-2xl"></i>
                        </div>
                        <span class="text-xs font-black text-amber-500 bg-amber-50 px-2 py-1 rounded-lg">{{ $stats['recruteurs_en_attente'] }} attente</span>
                    </div>
                    <p class="text-sm font-black text-gray-400 uppercase tracking-widest mb-1">Recruteurs</p>
                    <h3 class="text-4xl font-black text-gray-900">{{ number_format($stats['total_recruteurs']) }}</h3>
                </div>

                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-briefcase text-2xl"></i>
                        </div>
                        <span class="text-xs font-black text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg">Actives</span>
                    </div>
                    <p class="text-sm font-black text-gray-400 uppercase tracking-widest mb-1">Offres d'emploi</p>
                    <h3 class="text-4xl font-black text-gray-900">{{ number_format($stats['offres_actives']) }}</h3>
                </div>

                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-file-signature text-2xl"></i>
                        </div>
                        <span class="text-xs font-black text-purple-500 bg-purple-50 px-2 py-1 rounded-lg">Total</span>
                    </div>
                    <p class="text-sm font-black text-gray-400 uppercase tracking-widest mb-1">Candidatures</p>
                    <h3 class="text-4xl font-black text-gray-900">{{ number_format($stats['total_candidatures']) }}</h3>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-10">
                {{-- Historique des vérifications --}}
                <div class="xl:col-span-2 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                        <h4 class="text-lg font-black text-gray-900 uppercase tracking-tighter">Historique des Vérifications</h4>
                        <a href="{{ route('admin.recruteurs.verify') }}" class="text-sm font-bold text-indigo-600 bg-indigo-50 px-4 py-2 rounded-xl hover:bg-indigo-100 transition">Voir tout</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/50 text-xs font-black text-gray-400 uppercase tracking-widest">
                                <tr>
                                    <th class="px-8 py-4">Entreprise</th>
                                    <th class="px-8 py-4">Résultat du dossier</th>
                                    <th class="px-8 py-4">Date de traitement</th>
                                    <th class="px-8 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($historiqueVerifications as $audit)
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-8 py-6">
                                        <div class="font-black text-gray-900 tracking-tight">{{ $audit->recruteur->nom_entreprise ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-400 font-bold tracking-widest uppercase">Par {{ $audit->admin->user->name ?? 'Admin' }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        @if($audit->statut === 'approuve')
                                            <span class="bg-green-50 text-green-600 px-3 py-1 rounded-lg text-xs font-black uppercase tracking-widest border border-green-100 flex items-center w-max gap-2">
                                                <i class="fas fa-check-circle text-[8px]"></i> Approuvé
                                            </span>
                                        @else
                                            <span class="bg-red-50 text-red-600 px-3 py-1 rounded-lg text-xs font-black uppercase tracking-widest border border-red-100 flex items-center w-max gap-2">
                                                <i class="fas fa-times-circle text-[8px]"></i> Rejeté
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6">
                                        <p class="text-sm font-bold text-gray-700">{{ $audit->date_decision->translatedFormat('d M Y') }}</p>
                                        <p class="text-xs text-gray-400">{{ $audit->date_decision->diffForHumans() }}</p>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        @if($audit->recruteur && $audit->recruteur->user)
                                        <button onclick="window.location='{{ route('admin.messages') }}'" class="p-2.5 rounded-xl bg-gray-50 text-gray-400 hover:bg-indigo-600 hover:text-white transition-all shadow-sm opacity-0 group-hover:opacity-100">
                                            <i class="fas fa-envelope text-sm"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center text-gray-400 italic font-medium">Aucun historique de vérification disponible</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Nouveaux Inscrits --}}
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50">
                        <h4 class="text-lg font-black text-gray-900 uppercase tracking-tighter">Nouveaux Membres</h4>
                    </div>
                    <div class="p-8 space-y-6">
                        @foreach($recentUsers as $user)
                        <div class="flex items-center justify-between group cursor-pointer">
                            <div class="flex items-center">
                                <div class="relative">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" class="w-12 h-12 rounded-2xl mr-4 shadow-sm group-hover:scale-110 transition-transform">
                                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-white rounded-full flex items-center justify-center border border-gray-100">
                                        @if($user->role == 'recruteur') <i class="fas fa-building text-[8px] text-indigo-500"></i>
                                        @else <i class="fas fa-user text-[8px] text-blue-500"></i>
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400 font-medium">{{ $user->email }}</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-200 group-hover:text-indigo-400 transition-colors"></i>
                        </div>
                        @endforeach
                    </div>
                    <div class="p-8 bg-gray-50/50">
                        <a href="{{ route('admin.users') }}" class="block w-full text-center py-4 bg-white border border-gray-100 rounded-2xl text-sm font-black text-gray-600 hover:text-indigo-600 hover:border-indigo-100 transition shadow-sm">
                            Gérer tous les utilisateurs
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
