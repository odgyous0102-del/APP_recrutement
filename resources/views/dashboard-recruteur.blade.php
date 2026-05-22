@extends('layouts.app')

@section('title', 'Tableau de bord - Recruteur')

@section('meta-description', 'Gérez vos offres, examinez les candidatures et planifiez les entretiens')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
        }

        .tab-button.active {
            border-bottom-color: #7c3aed;
            color: #7c3aed;
            background: rgba(124, 58, 237, 0.05);
        }

        .application-card,
        .offer-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .application-card:hover,
        .offer-card:hover {
            transform: scale(1.01) translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-up {
            animation: slideUp 0.5s ease-out forwards;
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Dashboard Header -->
        <div class="bg-gradient-to-br from-indigo-700 via-purple-700 to-indigo-900 text-white relative overflow-hidden">
            <div class="container mx-auto px-8 py-12 relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="animate__animated animate__fadeInLeft">
                        <div class="flex items-center gap-4 mb-4">
                            <span
                                class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest border border-white/10">Espace
                                Recruteur</span>
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                            <span class="text-xs font-bold text-indigo-100">Connecté en tant que
                                {{ Auth::user()->name }}</span>
                        </div>
                        <h1 class="text-5xl font-black mb-3 tracking-tighter leading-tight">Propulsez votre <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-200 to-purple-200">Recrutement</span>
                        </h1>
                        <p class="text-indigo-100 text-lg font-medium opacity-90">Gérez vos talents, analysez vos
                            performances et trouvez la perle rare.</p>
                    </div>
                    <div class="flex gap-4 animate__animated animate__fadeInRight">
                        @if(Auth::user()->recruteur->is_verified)
                            <button onclick="openOfferModal()"
                                class="bg-white text-indigo-700 px-8 py-4 rounded-2xl font-black hover:bg-indigo-50 transition-all duration-300 shadow-2xl shadow-indigo-900/20 flex items-center gap-3 group">
                                <div
                                    class="w-8 h-8 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="fas fa-plus"></i>
                                </div>
                                Publier une offre
                            </button>
                        @else
                            <button disabled
                                class="bg-white/20 text-white/50 cursor-not-allowed px-8 py-4 rounded-2xl font-black flex items-center gap-3 border border-white/20">
                                <div class="w-8 h-8 bg-white/10 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-lock text-sm"></i>
                                </div>
                                Compte non activé
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            {{-- Decorative elements --}}
            <div class="absolute -right-20 -top-20 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute left-1/4 bottom-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Notifications -->
        @if(!Auth::user()->recruteur->is_verified)
            <div class="container mx-auto px-8 mt-6" style="position: relative; z-index: 50;">
                <div
                    style="background-color: #fffbeb; border: 2px solid #f59e0b; color: #92400e; padding: 1.25rem 1.5rem; border-radius: 1rem; display: flex; align-items: flex-start; gap: 1rem;">
                    <i class="fas fa-clock" style="font-size: 1.5rem; color: #f59e0b; margin-top: 2px; flex-shrink: 0;"></i>
                    <div>
                        <p style="font-weight: 800; font-size: 1rem; margin-bottom: 4px;">⚠️ Compte en attente de validation</p>
                        <p style="font-size: 0.85rem; opacity: 0.9; line-height: 1.5;">Un administrateur doit valider votre
                            compte avant que vous puissiez publier des offres et gérer des candidatures. <strong>Seule la
                                messagerie est disponible.</strong></p>
                    </div>
                </div>
            </div>
        @endif
        @if(session('success') || $errors->any())
            <div class="container mx-auto px-8 mt-4">
                @if(session('success'))
                    <div
                        class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl flex items-center gap-3 animate__animated animate__fadeIn">
                        <i class="fas fa-check-circle"></i>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div
                        class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mt-3 animate__animated animate__shakeX">
                        <div class="flex items-center gap-3 mb-2">
                            <i class="fas fa-exclamation-circle font-black"></i>
                            <span class="font-black text-sm uppercase tracking-widest">Oups ! Quelques erreurs sont survenues
                                :</span>
                        </div>
                        <ul class="list-disc list-inside text-xs font-bold space-y-1 ml-6">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
            </div>
        @endif

        <!-- Stats Overview -->
        <div class="container mx-auto px-8"
            style="{{ Auth::user()->recruteur->is_verified ? 'margin-top: -3rem;' : 'margin-top: 1.5rem;' }}">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-8">
                <div class="bg-white rounded-[2rem] shadow-xl p-8 border border-white/50 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group animate__animated animate__fadeInUp cursor-pointer"
                    onclick="switchTab('offers')"
                    style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-inner">
                            <i class="fas fa-briefcase text-2xl"></i>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Actives</span>
                            <p class="text-xs font-bold text-gray-400 mt-1">{{ $totalOffres }} totales</p>
                        </div>
                    </div>
                    <p class="text-sm font-black text-gray-500 uppercase tracking-widest mb-1">Offres Emploi</p>
                    <h3 class="text-4xl font-black text-gray-900 leading-none">{{ $offresActives }}</h3>
                </div>

                <div class="bg-white rounded-[2rem] shadow-xl p-8 border border-white/50 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group animate__animated animate__fadeInUp cursor-pointer"
                    onclick="switchTab('applications')"
                    style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-inner">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Total</span>
                            <p class="text-xs font-bold text-gray-400 mt-1">Reçues</p>
                        </div>
                    </div>
                    <p class="text-sm font-black text-gray-500 uppercase tracking-widest mb-1">Candidatures</p>
                    <h3 class="text-4xl font-black text-gray-900 leading-none">{{ $totalCandidatures }}</h3>
                </div>

                <div class="bg-white rounded-[2rem] shadow-xl p-8 border border-white/50 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group animate__animated animate__fadeInUp cursor-pointer"
                    onclick="switchTab('interviews')"
                    style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-inner">
                            <i class="fas fa-calendar-alt text-2xl"></i>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-black text-amber-400 uppercase tracking-widest">Planning</span>
                            <p class="text-xs font-bold text-gray-400 mt-1">À venir</p>
                        </div>
                    </div>
                    <p class="text-sm font-black text-gray-500 uppercase tracking-widest mb-1">Entretiens</p>
                    <h3 class="text-4xl font-black text-gray-900 leading-none">{{ $totalEntretiens }}</h3>
                </div>

                <div class="bg-white rounded-[2rem] shadow-xl p-8 border border-white/50 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group animate__animated animate__fadeInUp cursor-pointer"
                    onclick="switchTab('messages')"
                    style="animation-delay: 0.35s">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-inner">
                            <i class="fas fa-comments text-2xl"></i>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-black text-rose-400 uppercase tracking-widest">Nouveaux</span>
                            <p class="text-xs font-bold text-gray-400 mt-1">Non lus</p>
                        </div>
                    </div>
                    <p class="text-sm font-black text-gray-500 uppercase tracking-widest mb-1">Messages</p>
                    <h3 id="stat-unread-messages-recruteur" class="text-4xl font-black text-gray-900 leading-none">{{ $messages->where('destinataire_id', Auth::id())->whereNull('lu_at')->count() }}</h3>
                </div>

                <div class="bg-white rounded-[2rem] shadow-xl p-8 border border-white/50 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group animate__animated animate__fadeInUp cursor-pointer"
                    onclick="switchTab('analytics')"
                    style="animation-delay: 0.4s">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-inner">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Taux</span>
                            <p class="text-xs font-bold text-gray-400 mt-1">Succès</p>
                        </div>
                    </div>
                    <p class="text-sm font-black text-gray-500 uppercase tracking-widest mb-1">Conversion</p>
                    <h3 class="text-4xl font-black text-gray-900 leading-none">{{ $tauxConversion }}<span
                            class="text-lg ml-1 text-gray-400">%</span></h3>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-8">
            <!-- Tab Navigation -->
            <div class="bg-white rounded-xl shadow-lg mb-8">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button onclick="switchTab('offers')" id="offers-tab"
                            class="tab-button active px-6 py-4 border-b-2 border-purple-500 text-purple-600 font-medium">
                            <i class="fas fa-briefcase mr-2"></i>
                            Mes offres
                        </button>
                        <button onclick="switchTab('applications')" id="applications-tab"
                            class="tab-button px-6 py-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                            <i class="fas fa-users mr-2"></i>
                            Candidatures
                            @if($candidatures->whereIn('statut', ['en_attente', null])->count() > 0)
                                <span id="tab-badge-applications" class="ml-2 bg-red-500 text-white text-[10px] font-black rounded-full px-2 py-0.5 animate-pulse">
                                    {{ $candidatures->whereIn('statut', ['en_attente', null])->count() }}
                                </span>
                            @endif
                        </button>
                        <button onclick="switchTab('interviews')" id="interviews-tab"
                            class="tab-button px-6 py-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Entretiens
                        </button>
                        <button onclick="switchTab('messages')" id="messages-tab"
                            class="tab-button px-6 py-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                            <i class="fas fa-comments mr-2"></i>
                            Messagerie
                            @if($messages->where('destinataire_id', Auth::id())->whereNull('lu_at')->count() > 0)
                                <span id="tab-badge-messages-recruteur" class="ml-2 bg-red-500 text-white text-[10px] font-black rounded-full px-2 py-0.5 animate-pulse">
                                    {{ $messages->where('destinataire_id', Auth::id())->whereNull('lu_at')->count() }}
                                </span>
                            @endif
                        </button>
                        <button onclick="switchTab('evaluations')" id="evaluations-tab"
                            class="tab-button px-6 py-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                            <i class="fas fa-star-half-alt mr-2"></i>
                            Évaluations
                        </button>
                        <button onclick="switchTab('analytics')" id="analytics-tab"
                            class="tab-button px-6 py-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Analytics
                        </button>
                        <button onclick="window.location.href='{{ route('recruteur.network') }}'"
                            class="tab-button px-6 py-4 border-b-2 border-transparent text-indigo-600 hover:text-indigo-800 font-bold">
                            <i class="fas fa-network-wired mr-2"></i>
                            Réseau
                        </button>
                        <button onclick="switchTab('profile')" id="profile-tab"
                            class="tab-button px-6 py-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                            <i class="fas fa-user-cog mr-2"></i>
                            Mon Profil
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Offers Tab -->
                    <div id="offers-content" class="tab-content">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-semibold text-gray-900">Vos offres d'emploi</h3>
                            <div class="flex space-x-3">
                                <select
                                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option>Toutes les offres</option>
                                    <option>Actives</option>
                                    <option>En attente</option>
                                    <option>Expirées</option>
                                </select>
                                <div class="relative">
                                    <input type="text" placeholder="Rechercher..."
                                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4" id="offers-list">
                            @forelse($offres as $offre)
                                <div class="bg-white rounded-lg p-6 hover:shadow-md transition-all duration-200 border border-gray-200 offer-card"
                                    data-offre-id="{{ $offre->id }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-semibold text-gray-900">{{ $offre->titre }}</h4>
                                            <p class="text-gray-600 mt-1">{{ $offre->recruteur->nom_entreprise }}</p>
                                            <div class="flex flex-wrap gap-2 mt-3">
                                                <span
                                                    class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm">{{ ucfirst($offre->type_contrat) }}</span>
                                                <span
                                                    class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">{{ $offre->lieu }}</span>
                                                @if($offre->salaire_min && $offre->salaire_max)
                                                    <span
                                                        class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">{{ number_format($offre->salaire_min, 0, '.', ' ') }}K-{{ number_format($offre->salaire_max, 0, '.', ' ') }}K€</span>
                                                @endif
                                            </div>
                                            <p class="text-gray-700 mt-3 line-clamp-2">
                                                {{ Str::limit($offre->description, 100) }}
                                            </p>
                                        </div>
                                        <div class="ml-6 text-right">
                                            <span
                                                class="inline-block px-3 py-1 bg-{{ $offre->statut == 'publie' ? 'green' : ($offre->statut == 'brouillon' ? 'yellow' : 'gray') }}-100 text-{{ $offre->statut == 'publie' ? 'green' : ($offre->statut == 'brouillon' ? 'yellow' : 'gray') }}-700 rounded-full text-sm mb-2">
                                                {{ ucfirst($offre->statut) }}
                                            </span>
                                            <div class="text-sm text-gray-500">
                                                <p>{{ $offre->candidatures ? $offre->candidatures->count() : 0 }} candidats</p>
                                                <p>Publiée {{ $offre->created_at->diffForHumans() }}</p>
                                            </div>
                                            <div class="flex space-x-2 mt-3">
                                                <button
                                                    onclick="openEditOfferModal({{ $offre->id }}, {{ json_encode($offre->titre) }}, {{ json_encode($offre->type_contrat) }}, {{ json_encode($offre->lieu) }}, {{ json_encode((string) ($offre->salaire_min ?? '')) }}, {{ json_encode((string) ($offre->salaire_max ?? '')) }}, {{ json_encode($offre->description) }}, {{ json_encode(is_array($offre->competences_req) ? json_encode($offre->competences_req) : ($offre->competences_req ?? '')) }}, {{ json_encode($offre->statut) }}, {{ json_encode($offre->experience_req ?? 'debutant') }}, {{ json_encode($offre->teletravail ?? 'non') }}, {{ json_encode((string) ($offre->nb_postes ?? 1)) }}, {{ json_encode($offre->date_limite ? $offre->date_limite->format('Y-m-d') : '') }})"
                                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                    title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button onclick="deleteOffer({{ $offre->id }})"
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <i class="fas fa-briefcase text-gray-300 text-6xl mb-4"></i>
                                    <p class="text-gray-500 text-lg">Aucune offre publiée</p>
                                    <button onclick="openOfferModal()"
                                        class="mt-4 bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                                        Publier votre première offre
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Applications Tab -->
                    <div id="applications-content" class="tab-content hidden">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-semibold text-gray-900">Candidatures à examiner</h3>
                            <div class="flex space-x-3">
                                <select
                                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option>Toutes les candidatures</option>
                                    <option>Nouvelles</option>
                                    <option>En cours</option>
                                    <option>Acceptées</option>
                                    <option>Refusées</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-4" id="applications-list">
                            @forelse($candidatures as $candidature)
                                <div id="app-card-{{ $candidature->id }}"
                                    class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-all duration-200 application-card">
                                    <div class="flex justify-between items-start">
                                        <div class="flex space-x-4">
                                            <div
                                                class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold text-lg overflow-hidden border-2 border-white shadow-sm">
                                                @if($candidature->candidat->user->profile_picture)
                                                    <img src="{{ asset('storage/' . $candidature->candidat->user->profile_picture) }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    {{ substr($candidature->candidat->user->name, 0, 1) }}
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <h4 class="text-lg font-semibold text-gray-900">
                                                        {{ $candidature->candidat->user->name }}
                                                    </h4>
                                                    @if($candidature->created_at->isAfter(now()->subDays(2)) && in_array($candidature->statut, ['en_attente', null]))
                                                        <span id="new-badge-{{ $candidature->id }}"
                                                            class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">Nouveau</span>
                                                    @endif
                                                    <span
                                                        class="text-sm text-gray-500">{{ $candidature->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-gray-600 mb-2">
                                                    {{ $candidature->candidat->titre_professionnel ?? 'Candidat' }}
                                                </p>
                                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                    <span><i
                                                            class="fas fa-briefcase mr-1"></i>{{ $candidature->offre->titre }}</span>
                                                    <span><i
                                                            class="fas fa-map-marker-alt mr-1"></i>{{ $candidature->offre->lieu }}</span>
                                                    @if($candidature->score_matching)
                                                        <span><i
                                                                class="fas fa-percentage mr-1"></i>{{ $candidature->score_matching }}%
                                                            match</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @php
                                                $cvPathRaw = $candidature->cv_path ?: ($candidature->candidat->cv_path ?? null);
                                            @endphp
                                            @if($cvPathRaw)
                                                <a href="{{ asset('storage/' . $cvPathRaw) }}" target="_blank"
                                                    title="Télécharger le CV"
                                                    class="p-2 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-all shadow-sm">
                                                    <i class="fas fa-file-download"></i>
                                                </a>
                                            @endif
                                            <button onclick="viewApplication({{ $candidature->id }})"
                                                class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-all shadow-md text-sm font-bold flex items-center gap-2">
                                                <i class="fas fa-eye"></i>Voir
                                            </button>
                                            <button onclick="viewApplication({{ $candidature->id }}, true)"
                                                class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm text-sm font-bold flex items-center gap-2">
                                                <i class="fas fa-comments"></i>Message
                                            </button>
                                            <button onclick="openInterviewModal({{ $candidature->id }})"
                                                class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm text-sm font-bold flex items-center gap-2">
                                                <i class="fas fa-calendar-alt"></i>Entretien
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                                    <i class="fas fa-user-friends text-gray-300 text-6xl mb-4"></i>
                                    <p class="text-gray-500 text-lg">Aucune candidature reçue pour le moment.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Interviews Tab -->
                    <div id="interviews-content" class="tab-content hidden">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-semibold text-gray-900">Planning des entretiens</h3>
                            <button onclick="openInterviewModal()"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Planifier un entretien
                            </button>
                        </div>

                        <!-- Calendar View -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-lg font-semibold">Calendrier</h4>
                                <div class="flex space-x-2">
                                    <button class="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded">Semaine</button>
                                    <button class="px-3 py-1 bg-purple-600 text-white rounded">Mois</button>
                                </div>
                            </div>
                            <div class="grid grid-cols-7 gap-2 text-center">
                                <div class="text-xs font-medium text-gray-500 py-2">Lun</div>
                                <div class="text-xs font-medium text-gray-500 py-2">Mar</div>
                                <div class="text-xs font-medium text-gray-500 py-2">Mer</div>
                                <div class="text-xs font-medium text-gray-500 py-2">Jeu</div>
                                <div class="text-xs font-medium text-gray-500 py-2">Ven</div>
                                <div class="text-xs font-medium text-gray-500 py-2">Sam</div>
                                <div class="text-xs font-medium text-gray-500 py-2">Dim</div>

                                <!-- Calendar days -->
                                <div class="py-2 text-gray-400">29</div>
                                <div class="py-2 text-gray-400">30</div>
                                <div class="py-2 text-gray-400">31</div>
                                <div class="py-2">1</div>
                                <div class="py-2">2</div>
                                <div class="py-2">3</div>
                                <div class="py-2">4</div>
                                <div class="py-2">5</div>
                                <div class="py-2">6</div>
                                <div class="py-2">7</div>
                                <div class="py-2">8</div>
                                <div class="py-2">9</div>
                                <div class="py-2 bg-purple-100 rounded">10</div>
                                <div class="py-2">11</div>
                            </div>
                        </div>

                        <!-- Interviews List -->
                        <div class="space-y-4">
                            @forelse($entretiens as $entretien)
                                <div
                                    class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h4 class="text-lg font-semibold">
                                                    {{ $entretien->candidature->candidat->user->name }}
                                                </h4>
                                                <span
                                                    class="px-3 py-1 bg-{{ $entretien->statut == 'confirme' ? 'green' : ($entretien->statut == 'annule' ? 'red' : 'yellow') }}-100 text-{{ $entretien->statut == 'confirme' ? 'green' : ($entretien->statut == 'annule' ? 'red' : 'yellow') }}-800 text-xs font-medium rounded-full">
                                                    {{ ucfirst($entretien->statut) }}
                                                </span>
                                            </div>
                                            <p class="text-gray-600 mb-2">{{ $entretien->candidature->offre->titre }}</p>
                                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                <span><i
                                                        class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($entretien->date_entretien)->format('d M Y') }}</span>
                                                <span><i
                                                        class="fas fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($entretien->heure_entretien)->format('H:i') }}</span>
                                                <span><i
                                                        class="fas fa-video mr-1"></i>{{ ucfirst($entretien->type_entretien) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            @if($entretien->statut != 'annule')
                                                <button
                                                    onclick="openEvaluationInterviewModal({{ $entretien->id }}, '{{ addslashes($entretien->candidature->candidat->user->name) }}', '{{ addslashes($entretien->candidature->offre->titre) }}')"
                                                    class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                                                    <i class="fas fa-star mr-2"></i>Évaluer
                                                </button>
                                            @endif
                                            @if($entretien->type_entretien == 'visio' || $entretien->type_entretien == 'visioconference')
                                                <button
                                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                                    <i class="fas fa-video mr-2"></i>Rejoindre
                                                </button>
                                            @endif
                                            <form action="{{ route('entretiens.annuler', $entretien->id) }}" method="POST"
                                                onsubmit="return confirm('Annuler cet entretien ?')">
                                                @csrf
                                                <button type="submit"
                                                    class="px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                                                    <i class="fas fa-times mr-2"></i>Annuler
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                                    <i class="fas fa-calendar-times text-gray-300 text-6xl mb-4"></i>
                                    <p class="text-gray-500 text-lg">Aucun entretien prévu prochainement.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <!-- Evaluations Tab -->
                    <div id="evaluations-content" class="tab-content hidden">
                        <div class="mb-8">
                            <h3 class="text-2xl font-black text-gray-900">Synthèse des Évaluations</h3>
                            <p class="text-gray-500">Retrouvez les scores détaillés de vos candidats par offre d'emploi.</p>
                        </div>

                        <div class="space-y-12">
                            @forelse($offresEvaluations as $offre)
                                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                    <div
                                        class="bg-gray-50 px-8 py-4 border-b border-gray-100 flex justify-between items-center">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-10 h-10 bg-purple-600 rounded-xl flex items-center justify-center text-white">
                                                <i class="fas fa-briefcase"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-black text-gray-900 uppercase tracking-tight">
                                                    {{ $offre->titre }}
                                                </h4>
                                                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">
                                                    {{ $offre->lieu }} • {{ $offre->candidatures->count() }} candidats
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-0">
                                        <table class="w-full text-left">
                                            <thead
                                                class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                                <tr>
                                                    <th class="px-8 py-4">Candidat</th>
                                                    <th class="px-4 py-4">Tech</th>
                                                    <th class="px-4 py-4">Com</th>
                                                    <th class="px-4 py-4">Motiv</th>
                                                    <th class="px-4 py-4">Fit</th>
                                                    <th class="px-4 py-4">Moyenne</th>
                                                    <th class="px-4 py-4 text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-50">
                                                @php $hasEval = false; @endphp
                                                @foreach($offre->candidatures as $candidature)
                                                    @foreach($candidature->entretiens as $entretien)
                                                        @foreach($entretien->evaluations as $evaluation)
                                                            @php $hasEval = true; @endphp
                                                            <tr class="hover:bg-gray-50 transition-colors group">
                                                                <td class="px-8 py-4">
                                                                    <div class="flex items-center gap-3">
                                                                        <div
                                                                            class="w-8 h-8 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center font-bold text-xs">
                                                                            {{ substr($candidature->candidat->user->name, 0, 1) }}
                                                                        </div>
                                                                        <div>
                                                                            <p class="text-sm font-bold text-gray-900">
                                                                                {{ $candidature->candidat->user->name }}
                                                                            </p>
                                                                            <p
                                                                                class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                                                                {{ $candidature->statut }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="px-4 py-4">
                                                                    <span
                                                                        class="text-sm font-black text-gray-700">{{ $evaluation->competences_techniques }}/5</span>
                                                                </td>
                                                                <td class="px-4 py-4">
                                                                    <span
                                                                        class="text-sm font-black text-gray-700">{{ $evaluation->communication }}/5</span>
                                                                </td>
                                                                <td class="px-4 py-4">
                                                                    <span
                                                                        class="text-sm font-black text-gray-700">{{ $evaluation->motivation }}/5</span>
                                                                </td>
                                                                <td class="px-4 py-4">
                                                                    <span
                                                                        class="text-sm font-black text-gray-700">{{ $evaluation->culture_fit }}/5</span>
                                                                </td>
                                                                <td class="px-4 py-4">
                                                                    <div class="flex items-center gap-2">
                                                                        <span
                                                                            class="px-2 py-1 bg-purple-100 text-purple-700 rounded-lg font-black text-sm">{{ number_format($evaluation->note_globale, 1) }}</span>
                                                                        <div class="flex text-[8px] text-amber-400">
                                                                            @for($i = 1; $i <= 5; $i++)
                                                                                <i
                                                                                    class="fas fa-star {{ $i <= $evaluation->note_globale ? '' : 'text-gray-200' }}"></i>
                                                                            @endfor
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="px-4 py-4 text-center">
                                                                    <button onclick="viewApplication({{ $candidature->id }})"
                                                                        class="p-2 text-gray-400 hover:text-purple-600 transition-colors">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                @endforeach
                                                @if(!$hasEval)
                                                    <tr>
                                                        <td colspan="7" class="px-8 py-8 text-center text-gray-400 italic text-sm">
                                                            Aucune évaluation enregistrée pour cette offre.
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                                    <div
                                        class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i class="fas fa-star-half-alt text-gray-300 text-3xl"></i>
                                    </div>
                                    <p class="text-gray-500 font-bold text-lg">Aucune offre disponible.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Analytics Tab -->
                    <div id="analytics-content" class="tab-content hidden">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Analytics et Performance</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Chart 1 -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                                <h4 class="text-lg font-semibold mb-4 text-gray-700">Aperçu des candidatures</h4>
                                <div class="h-64 relative">
                                    <canvas id="applicationsChart"></canvas>
                                </div>
                            </div>

                            <!-- Chart 2 -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                                <h4 class="text-lg font-semibold mb-4 text-gray-700">Répartition par statut</h4>
                                <div class="h-64 relative">
                                    <canvas id="statusChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- Analytics Tab (Already closed by previous div) -->
                    </div>

                    <!-- Messages Tab -->
                    <div id="messages-content" class="tab-content hidden">
                        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm flex h-[70vh] overflow-hidden">
                            <!-- Chat List -->
                            <div class="w-1/3 border-r border-slate-100 flex flex-col">
                                <div class="p-6 border-b border-slate-100">
                                    <h3 class="font-black text-slate-800 text-lg">Conversations</h3>
                                </div>
                                <div class="flex-1 overflow-y-auto divide-y divide-slate-50" id="chat-list">
                                    @forelse($messages->groupBy('candidature_id') as $candidatureId => $msgs)
                                        @php
                                            $lastMsg = $msgs->first();
                                            $correspondant = $lastMsg->candidature?->candidat?->user ?? ($lastMsg->expediteur_id == Auth::id() ? $lastMsg->destinataire : $lastMsg->expediteur);
                                            $isUnread = $msgs->where('destinataire_id', Auth::id())->whereNull('lu_at')->count() > 0;
                                        @endphp
                                        @if($correspondant)
                                            <div class="p-6 hover:bg-slate-50 transition-all cursor-pointer flex gap-4 {{ $isUnread ? 'bg-indigo-50/30' : '' }} chat-item-{{ $correspondant->id }}"
                                                onclick="openChat('{{ $candidatureId ?: 'null-' . $correspondant->id }}')">
                                                <div
                                                    class="w-12 h-12 rounded-full overflow-hidden shadow-sm flex-shrink-0 relative">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($correspondant->name) }}&background=6366f1&color=fff"
                                                        alt="">
                                                    @if($isUnread)
                                                        <span
                                                            class="absolute top-0 right-0 w-3 h-3 bg-red-500 border-2 border-white rounded-full bg-rose-500"></span>
                                                    @endif
                                                </div>
                                                <div class="overflow-hidden flex-1">
                                                    <div class="flex justify-between items-baseline mb-1">
                                                        <h4 class="font-bold text-slate-800 text-sm truncate">
                                                            {{ $correspondant->name }}
                                                        </h4>
                                                        <span
                                                            class="text-[10px] text-slate-400 font-bold">{{ $lastMsg->created_at->shortRelativeDiffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-[10px] text-indigo-400 font-bold truncate mb-1">
                                                        {{ $lastMsg->candidature?->offre?->titre ?? 'Discussion directe' }}
                                                    </p>
                                                    <p
                                                        class="text-xs text-slate-500 truncate font-medium {{ $isUnread ? 'text-slate-800 font-bold' : '' }}">
                                                        {{ $lastMsg->contenu }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <div class="p-8 text-center text-slate-400">
                                            <i class="fas fa-inbox text-3xl mb-3"></i>
                                            <p class="text-sm font-bold">Aucune conversation</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            <!-- Chat Window -->
                            <div class="flex-1 flex flex-col bg-slate-50/50">
                                <div id="no-chat-selected"
                                    class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                                    <div
                                        class="w-24 h-24 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 text-4xl mb-6">
                                        <i class="fas fa-comments"></i>
                                    </div>
                                    <h3 class="font-black text-slate-400 uppercase tracking-widest text-sm">Sélectionnez une
                                        conversation</h3>
                                </div>
                                <div id="chat-window" class="hidden flex-1 flex flex-col h-full">
                                    <div
                                        class="p-6 bg-white border-b border-slate-100 flex items-center gap-4 shadow-sm z-10">
                                        <div class="w-10 h-10 rounded-full overflow-hidden shadow-sm flex-shrink-0">
                                            <img id="chat-user-avatar" src="" alt="">
                                        </div>
                                        <div>
                                            <h4 id="chat-user-name" class="font-bold text-slate-800 leading-tight"></h4>
                                            <p id="chat-offer-title"
                                                class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">
                                            </p>
                                        </div>
                                    </div>
                                    <div id="chat-messages" class="flex-1 p-8 overflow-y-auto space-y-4">
                                        <!-- Messages will be loaded here -->
                                    </div>
                                    <div class="p-6 bg-white border-t border-slate-100">
                                        <form id="chat-form" action="{{ route('recruteur.messages.store') }}" method="POST"
                                            class="flex gap-4" data-ajax>
                                            @csrf
                                            <input type="hidden" name="candidature_id" id="chat-candidature-id">
                                            <input type="hidden" name="destinataire_id" id="chat-destinataire-id">
                                            <input type="text" name="contenu" placeholder="Écrivez votre message..."
                                                required
                                                class="flex-1 px-6 py-4 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-medium">
                                            <button type="submit"
                                                class="bg-indigo-600 text-white w-14 h-14 rounded-2xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 flex items-center justify-center">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Tab -->
                    <div id="profile-content" class="tab-content hidden">
                        <div class="max-w-4xl mx-auto">
                            <div class="mb-8">
                                <h3 class="text-2xl font-black text-gray-900">Paramètres du Profil</h3>
                                <p class="text-gray-500 font-bold uppercase tracking-widest text-xs mt-1">Gérez vos
                                    informations personnelles et celles de votre entreprise</p>
                            </div>

                            <form action="{{ route('recruteur.profile.update') }}" method="POST"
                                enctype="multipart/form-data" class="space-y-8">
                                @csrf
                                <!-- Informations Personnelles -->
                                <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                                    <h4
                                        class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <i class="fas fa-user text-purple-600"></i> Informations Personnelles
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label
                                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nom
                                                complet</label>
                                            <input type="text" name="name" value="{{ Auth::user()->name }}"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 outline-none font-bold text-sm"
                                                required>
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Adresse
                                                Email</label>
                                            <input type="email" name="email" value="{{ Auth::user()->email }}"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 outline-none font-bold text-sm"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informations Entreprise -->
                                <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                                    <h4
                                        class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <i class="fas fa-building text-blue-600"></i> Informations Entreprise
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div class="space-y-2">
                                            <label
                                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nom
                                                de l'entreprise</label>
                                            <input type="text" name="nom_entreprise"
                                                value="{{ Auth::user()->recruteur->nom_entreprise }}"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 outline-none font-bold text-sm"
                                                required>
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Secteur
                                                d'activité</label>
                                            <input type="text" name="secteur_activite"
                                                value="{{ Auth::user()->recruteur->secteur_activite }}"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 outline-none font-bold text-sm">
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Taille
                                                de l'entreprise</label>
                                            <select name="taille_entreprise"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 outline-none font-bold text-sm">
                                                <option value="startup" {{ Auth::user()->recruteur->taille_entreprise == 'startup' ? 'selected' : '' }}>Startup (1-10 employés)</option>
                                                <option value="pme" {{ Auth::user()->recruteur->taille_entreprise == 'pme' ? 'selected' : '' }}>PME (11-250 employés)</option>
                                                <option value="grande_entreprise" {{ Auth::user()->recruteur->taille_entreprise == 'grande_entreprise' ? 'selected' : '' }}>Grande Entreprise (250-5000 employés)</option>
                                                <option value="multinationale" {{ Auth::user()->recruteur->taille_entreprise == 'multinationale' ? 'selected' : '' }}>Multinationale (+5000 employés)</option>
                                            </select>
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Site
                                                Web (URL)</label>
                                            <input type="url" name="site_web"
                                                value="{{ Auth::user()->recruteur->site_web }}"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 outline-none font-bold text-sm"
                                                placeholder="https://...">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                                        <div class="md:col-span-1">
                                            <div class="relative group">
                                                <div
                                                    class="w-full aspect-square bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden">
                                                    @if(Auth::user()->recruteur->logo_entreprise)
                                                        <img src="{{ asset('storage/' . Auth::user()->recruteur->logo_entreprise) }}"
                                                            class="w-full h-full object-cover">
                                                    @else
                                                        <i class="fas fa-image text-3xl text-gray-300"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="md:col-span-2 space-y-2">
                                            <label
                                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Logo
                                                de l'entreprise</label>
                                            <input type="file" name="logo_entreprise"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 outline-none font-bold text-sm bg-gray-50">
                                            <p class="text-[10px] text-gray-400 font-bold ml-1 uppercase">Format: JPG, PNG •
                                                Max: 2MB</p>
                                        </div>
                                    </div>

                                    <div class="mt-6 space-y-2">
                                        <label
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Description
                                            de l'entreprise</label>
                                        <textarea name="description_ent" rows="5"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 outline-none font-medium text-sm resize-none">{{ Auth::user()->recruteur->description_ent }}</textarea>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="w-full py-5 bg-gray-900 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-black transition-all shadow-xl shadow-gray-200 flex items-center justify-center gap-3">
                                    <i class="fas fa-save"></i> Enregistrer les modifications
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Offer Modal -->
    <div id="offerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold">Publier une nouvelle offre</h3>
                    <button onclick="closeOfferModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <form id="offerForm" class="p-6 space-y-4" action="{{ route('offres.store') }}" method="POST">
                @csrf

                <!-- $table->string('titre') -->
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">Titre du poste</label>
                    <input type="text" id="titre" name="titre" required maxlength="255"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="ex: Développeur Full Stack">
                </div>

                <!-- $table->text('description') -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description du
                        poste</label>
                    <textarea id="description" name="description" rows="4" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Décrivez le poste, les missions et l'environnement de travail..."></textarea>
                </div>

                <!-- $table->enum('type_contrat', ['cdi', 'cdd', 'stage', 'alternance', 'freelance']) -->
                <!-- $table->string('lieu') -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="type_contrat" class="block text-sm font-medium text-gray-700 mb-2">Type de
                            contrat</label>
                        <select id="type_contrat" name="type_contrat" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Sélectionner...</option>
                            <option value="cdi">CDI</option>
                            <option value="cdd">CDD</option>
                            <option value="stage">Stage</option>
                            <option value="alternance">Alternance</option>
                            <option value="freelance">Freelance</option>
                        </select>
                    </div>
                    <div>
                        <label for="lieu" class="block text-sm font-medium text-gray-700 mb-2">Lieu de travail</label>
                        <input type="text" id="lieu" name="lieu" required maxlength="255"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="ex: Ouagadougou, Lyon, Remote">
                    </div>
                </div>

                <!-- $table->json('competences_req')->nullable() -->
                <div>
                    <label for="competences_req" class="block text-sm font-medium text-gray-700 mb-2">Compétences
                        requises</label>
                    <div class="space-y-2">
                        <div id="competencesList" class="flex flex-wrap gap-2 mb-2">
                            <!-- Les compétences seront ajoutées ici dynamiquement -->
                        </div>
                        <div class="flex gap-2">
                            <input type="text" id="competenceInput"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Ajouter une compétence (ex: PHP)">
                            <button type="button" onclick="addCompetence()"
                                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                <i class="fas fa-plus"></i> Ajouter
                            </button>
                        </div>
                        <input type="hidden" id="competences_req" name="competences_req">
                    </div>
                </div>

                <!-- $table->enum('teletravail', ['non', 'partiel', 'total'])->default('non') -->
                <!-- $table->enum('experience_req', ['debutant', 'junior', 'confirme', 'senior', 'expert']) -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="teletravail" class="block text-sm font-medium text-gray-700 mb-2">Télétravail</label>
                        <select id="teletravail" name="teletravail"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="non" selected>Non</option>
                            <option value="partiel">Partiel</option>
                            <option value="total">Total</option>
                        </select>
                    </div>
                    <div>
                        <label for="experience_req" class="block text-sm font-medium text-gray-700 mb-2">Expérience
                            requise</label>
                        <select id="experience_req" name="experience_req"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="debutant" selected>Débutant</option>
                            <option value="junior">Junior</option>
                            <option value="confirme">Confirmé</option>
                            <option value="senior">Senior</option>
                            <option value="expert">Expert</option>
                        </select>
                    </div>
                </div>

                <!-- $table->decimal('salaire_min', 10, 2)->nullable() -->
                <!-- $table->decimal('salaire_max', 10, 2)->nullable() -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="salaire_min" class="block text-sm font-medium text-gray-700 mb-2">Salaire minimum
                            (€)</label>
                        <input type="number" id="salaire_min" name="salaire_min" step="0.01" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="ex: 35000">
                    </div>
                    <div>
                        <label for="salaire_max" class="block text-sm font-medium text-gray-700 mb-2">Salaire maximum
                            (€)</label>
                        <input type="number" id="salaire_max" name="salaire_max" step="0.01" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="ex: 55000">
                    </div>
                </div>

                <!-- $table->integer('nb_postes')->default(1) -->
                <!-- $table->date('date_limite')->nullable() -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="nb_postes" class="block text-sm font-medium text-gray-700 mb-2">Nombre de postes</label>
                        <input type="number" id="nb_postes" name="nb_postes" min="1" value="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="ex: 1">
                    </div>
                    <div>
                        <label for="date_limite" class="block text-sm font-medium text-gray-700 mb-2">Date limite de
                            candidature</label>
                        <input type="date" id="date_limite" name="date_limite"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>

                <!-- $table->enum('statut', ['brouillon', 'publie', 'archive'])->default('brouillon') -->
                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut de l'offre</label>
                    <select id="statut" name="statut" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Choisir le statut...</option>
                        <option value="publie">Publiée (visible par les candidats)</option>
                        <option value="brouillon" selected>Brouillon (non visible)</option>
                        <option value="archive">Archivée</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeOfferModal()"
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        Publier l'offre
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Offer Modal -->
    <div id="editOfferModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold">Modifier l'offre</h3>
                    <button onclick="closeEditOfferModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <form id="editOfferForm" class="p-6 space-y-4" method="POST" action="#">
                @csrf
                <input type="hidden" id="edit_offre_id" name="offre_id">
                <input type="hidden" name="_method" value="PUT">

                <!-- titre | string | required | max:255 -->
                <div>
                    <label for="edit_titre" class="block text-sm font-medium text-gray-700 mb-2">
                        Titre du poste <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="edit_titre" name="titre" required maxlength="255"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="ex: Développeur Full Stack">
                </div>

                <!-- description | text | required | min:10 -->
                <div>
                    <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description du poste <span class="text-red-500">*</span>
                    </label>
                    <textarea id="edit_description" name="description" rows="4" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Décrivez le poste, les missions..."></textarea>
                </div>

                <!-- type_contrat | enum | required — lieu | string | required -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit_type_contrat" class="block text-sm font-medium text-gray-700 mb-2">
                            Type de contrat <span class="text-red-500">*</span>
                        </label>
                        <select id="edit_type_contrat" name="type_contrat" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Sélectionner...</option>
                            <option value="cdi">CDI</option>
                            <option value="cdd">CDD</option>
                            <option value="stage">Stage</option>
                            <option value="alternance">Alternance</option>
                            <option value="freelance">Freelance</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_lieu" class="block text-sm font-medium text-gray-700 mb-2">
                            Lieu de travail <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="edit_lieu" name="lieu" required maxlength="255"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="ex: Ouagadougou, Lyon, Remote">
                    </div>
                </div>

                <!-- teletravail | enum(non,partiel,total) | default:non — experience_req | enum | nullable -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit_teletravail"
                            class="block text-sm font-medium text-gray-700 mb-2">Télétravail</label>
                        <select id="edit_teletravail" name="teletravail"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="non">Non</option>
                            <option value="partiel">Partiel</option>
                            <option value="total">Total</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_experience_req" class="block text-sm font-medium text-gray-700 mb-2">Expérience
                            requise</label>
                        <select id="edit_experience_req" name="experience_req"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="debutant">Débutant (0–2 ans)</option>
                            <option value="junior">Junior (2–3 ans)</option>
                            <option value="confirme">Confirmé (3–5 ans)</option>
                            <option value="senior">Senior (5–8 ans)</option>
                            <option value="expert">Expert (8+ ans)</option>
                        </select>
                    </div>
                </div>

                <!-- competences_req | json | nullable -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Compétences requises
                        <span class="text-xs text-gray-400 font-normal ml-1">(optionnel)</span>
                    </label>
                    <div class="space-y-2">
                        <div id="editCompetencesList" class="flex flex-wrap gap-2 min-h-[2rem]"></div>
                        <div class="flex gap-2">
                            <input type="text" id="editCompetenceInput"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Ex: PHP, React, SQL…">
                            <button type="button" onclick="addEditCompetence()"
                                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                <i class="fas fa-plus"></i> Ajouter
                            </button>
                        </div>
                        <input type="hidden" id="edit_competences_req" name="competences_req">
                    </div>
                </div>

                <!-- salaire_min | decimal(10,2) | nullable — salaire_max | decimal(10,2) | nullable -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit_salaire_min" class="block text-sm font-medium text-gray-700 mb-2">
                            Salaire minimum (€)
                            <span class="text-xs text-gray-400 font-normal ml-1">(optionnel)</span>
                        </label>
                        <input type="number" id="edit_salaire_min" name="salaire_min" step="0.01" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="ex: 35000">
                    </div>
                    <div>
                        <label for="edit_salaire_max" class="block text-sm font-medium text-gray-700 mb-2">
                            Salaire maximum (€)
                            <span class="text-xs text-gray-400 font-normal ml-1">(optionnel)</span>
                        </label>
                        <input type="number" id="edit_salaire_max" name="salaire_max" step="0.01" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="ex: 55000">
                    </div>
                </div>

                <!-- nb_postes | integer | default:1 — date_limite | date | nullable -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit_nb_postes" class="block text-sm font-medium text-gray-700 mb-2">Nombre de
                            postes</label>
                        <input type="number" id="edit_nb_postes" name="nb_postes" min="1" value="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="edit_date_limite" class="block text-sm font-medium text-gray-700 mb-2">
                            Date limite
                            <span class="text-xs text-gray-400 font-normal ml-1">(optionnel)</span>
                        </label>
                        <input type="date" id="edit_date_limite" name="date_limite"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>

                <!-- statut | enum(brouillon,publie,archive) | required | default:brouillon -->
                <div>
                    <label for="edit_statut" class="block text-sm font-medium text-gray-700 mb-2">
                        Statut de l'offre <span class="text-red-500">*</span>
                    </label>
                    <select id="edit_statut" name="statut" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="brouillon">Brouillon (non visible)</option>
                        <option value="publie">Publiée (visible par les candidats)</option>
                        <option value="archive">Archivée</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeEditOfferModal()"
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        Mettre à jour l'offre
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Interview Modal -->
    <div id="interviewModal"
        class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden animate-fadeIn">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-indigo-600 text-white">
                <h3 class="font-black text-xl flex items-center gap-3">
                    <i class="fas fa-calendar-alt text-indigo-200"></i> Planifier un entretien
                </h3>
                <button onclick="closeInterviewModal()" class="text-white/80 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="interviewForm" action="{{ route('recruteur.entretiens.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100 flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xl">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="flex-1">
                        <label for="int_candidature_id"
                            class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1 block">Candidat et
                            Offre associée</label>
                        <select id="int_candidature_id" name="candidature_id" required
                            class="w-full bg-transparent border-b-2 border-indigo-200 outline-none focus:border-indigo-600 transition-all font-bold text-indigo-900 text-sm pb-1 cursor-pointer">
                            <option value="">Sélectionnez un candidat...</option>
                            @foreach($candidatures as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->candidat->user->name ?? 'Candidat inconnu' }}
                                    ({{ $c->offre->titre ?? 'Offre inconnue' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="date_entretien"
                            class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Date</label>
                        <input type="date" id="date_entretien" name="date_entretien" required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-bold text-slate-700 text-sm">
                    </div>
                    <div class="space-y-2">
                        <label for="heure_entretien"
                            class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Heure</label>
                        <input type="time" id="heure_entretien" name="heure_entretien" required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-bold text-slate-700 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="int_type" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Type
                            d'entretien</label>
                        <select id="int_type" name="type" required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-bold text-slate-700 text-sm cursor-pointer"
                            onchange="toggleLienVisio()">
                            <option value="visio">Visioconférence</option>
                            <option value="telephonique">Téléphonique</option>
                            <option value="physique">Présentiel</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="duree_minutes"
                            class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Durée</label>
                        <select id="duree_minutes" name="duree_minutes" required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-bold text-slate-700 text-sm cursor-pointer">
                            <option value="15">15 minutes</option>
                            <option value="30">30 minutes</option>
                            <option value="45">45 minutes</option>
                            <option value="60" selected>1 heure</option>
                            <option value="90">1 heure 30</option>
                            <option value="120">2 heures</option>
                        </select>
                    </div>
                </div>

                <div id="visio_container" class="space-y-2">
                    <label for="lien_visio" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Lien de
                        la visioconférence</label>
                    <input type="url" id="lien_visio" name="lien_visio"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-medium text-slate-700 text-sm"
                        placeholder="https://zoom.us/j/...">
                </div>

                <div class="space-y-2">
                    <label for="int_notes" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Notes /
                        Consignes</label>
                    <textarea id="int_notes" name="notes" rows="2"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-medium text-slate-700 text-sm resize-none"
                        placeholder="Préparer une présentation, etc..."></textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 flex gap-4 mt-4">
                    <button type="button" onclick="closeInterviewModal()"
                        class="flex-1 px-6 py-4 rounded-2xl font-black text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all uppercase tracking-widest text-xs">
                        Annuler
                    </button>
                    <button type="submit"
                        class="flex-[2] bg-indigo-600 text-white px-6 py-4 rounded-2xl font-black hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                        <i class="fas fa-calendar-check"></i> Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Message Modal -->
    <div id="messageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl max-w-md w-full mx-4 shadow-2xl transform transition-all duration-300">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-t-xl">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-white">Envoyer un message</h3>
                    <button onclick="closeMessageModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <form id="messageForm" action="{{ route('recruteur.messages.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="msg_candidature_id" name="candidature_id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">À : <span id="candidateName"
                            class="font-bold text-blue-600"></span></label>
                </div>

                <div>
                    <label for="contenu" class="block text-sm font-medium text-gray-700 mb-2">Votre message</label>
                    <textarea id="contenu" name="contenu" rows="5" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                        placeholder="Écrivez votre message ici..."></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeMessageModal()"
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium text-gray-700">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i>Envoyer le message
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Application Detail Modal -->
    <div id="applicationDetailModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4 modal-backdrop">
        <div
            class="bg-white rounded-3xl max-w-5xl w-full max-h-[90vh] overflow-hidden shadow-2xl transform transition-all duration-300 flex flex-col md:flex-row">

            <!-- Details Column (Left) -->
            <div class="flex-1 overflow-y-auto border-r border-gray-100 flex flex-col">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                    <h3 class="text-xl font-bold text-gray-900">Détails de la candidature</h3>
                    <button onclick="closeApplicationModal()"
                        class="text-gray-400 hover:text-gray-600 transition-colors md:hidden">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-8">
                    <div class="flex items-center space-x-6 mb-8">
                        <div id="app_avatar"
                            class="w-20 h-20 bg-purple-600 rounded-2xl flex items-center justify-center text-white text-3xl font-bold shadow-lg shadow-purple-100">
                        </div>
                        <div>
                            <h4 id="app_name" class="text-2xl font-black text-gray-900"></h4>
                            <p id="app_title" class="text-purple-600 font-bold text-lg"></p>
                            <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                                <span id="app_email"></span>
                                <span id="app_date"></span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Score de Matching
                            </p>
                            <p id="app_score" class="text-lg font-black text-purple-600"></p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Disponibilité</p>
                            <p id="app_dispo" class="text-lg font-black text-gray-700"></p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Prétentions</p>
                            <p id="app_salary" class="text-lg font-black text-gray-700"></p>
                        </div>
                    </div>

                    <!-- Expériences & Formations -->
                    <div class="space-y-6 mb-8">
                        <div>
                            <h5 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-3 flex items-center gap-2">
                                <i class="fas fa-briefcase text-purple-500"></i> Expériences Professionnelles
                            </h5>
                            <div id="app_experiences" class="space-y-3">
                                <!-- Injectées via JS -->
                            </div>
                        </div>

                        <div>
                            <h5 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-3 flex items-center gap-2">
                                <i class="fas fa-graduation-cap text-purple-500"></i> Formations
                            </h5>
                            <div id="app_formations" class="space-y-3">
                                <!-- Injectées via JS -->
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <h5
                                class="text-sm font-black text-gray-900 uppercase tracking-widest mb-3 flex items-center gap-2">
                                <i class="fas fa-file-alt text-purple-500"></i> Lettre de motivation
                            </h5>
                            <div id="app_motivation"
                                class="text-gray-600 leading-relaxed bg-gray-50 p-6 rounded-2xl border border-gray-100 whitespace-pre-line italic">
                            </div>
                        </div>

                        <div>
                            <h5
                                class="text-sm font-black text-gray-900 uppercase tracking-widest mb-3 flex items-center justify-between">
                                <span class="flex items-center gap-2"><i class="fas fa-file-pdf text-red-500"></i>
                                    Curriculum Vitae</span>
                                <div class="flex gap-2">
                                    <a id="app_cv_download" href="#" target="_blank"
                                        class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs text-gray-700 hover:bg-gray-50 font-bold transition-all flex items-center gap-2">
                                        <i class="fas fa-download"></i> Télécharger
                                    </a>
                                    <a id="app_cv_new_tab" href="#" target="_blank"
                                        class="px-3 py-1.5 bg-indigo-600 rounded-lg text-xs text-white hover:bg-indigo-700 font-bold transition-all flex items-center gap-2">
                                        <i class="fas fa-external-link-alt"></i> Ouvrir
                                    </a>
                                </div>
                            </h5>
                            <div id="cv_preview_container"
                                class="rounded-2xl border border-gray-200 bg-white overflow-hidden relative shadow-inner"
                                style="height: 500px;">
                                <iframe id="app_cv_iframe" src="" class="w-full h-full" frameborder="0"></iframe>
                                <div id="cv_no_preview"
                                    class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 bg-gray-50 hidden">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-file-alt text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-black text-gray-500 uppercase tracking-widest">Aucun CV
                                        disponible</p>
                                    <p class="text-xs text-gray-400 mt-1">Le candidat n'a pas fourni de document pour cette
                                        candidature.</p>
                                </div>
                                <div id="cv_loading"
                                    class="absolute inset-0 flex items-center justify-center bg-white z-20">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="w-10 h-10 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin">
                                        </div>
                                        <p
                                            class="text-[10px] font-black text-indigo-400 uppercase tracking-widest animate-pulse">
                                            Chargement du document...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-gray-50 border-t border-gray-100 flex gap-4 mt-auto">
                    <button onclick="closeApplicationModal()"
                        class="flex-1 py-4 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold hover:bg-gray-50 transition-all">
                        Fermer
                    </button>
                    <button id="app_action_btn"
                        class="flex-[2] py-4 bg-purple-600 text-white rounded-xl font-bold hover:bg-purple-700 shadow-lg shadow-purple-100 transition-all">
                        Prendre une décision
                    </button>
                </div>
            </div>

            <!-- Messages Column (Right) -->
            <div class="w-full md:w-96 flex flex-col bg-gray-50/50">
                <div class="p-6 border-b border-gray-100 bg-white flex justify-between items-center sticky top-0 z-10">
                    <h3 class="text-lg font-black text-gray-800 uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-comments text-purple-600"></i> Messages
                    </h3>
                    <button onclick="closeApplicationModal()"
                        class="text-gray-400 hover:text-gray-600 transition-colors hidden md:block">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div id="app_chat_messages" class="flex-1 p-6 overflow-y-auto space-y-4">
                    <!-- Messages populated via JS -->
                </div>

                <div class="p-6 bg-white border-t border-gray-100">
                    <form id="app-chat-form" onsubmit="handleAppChatSubmit(event)" class="flex gap-2" data-ajax>
                        @csrf
                        <input type="hidden" name="candidature_id" id="app_chat_candidature_id">
                        <input type="text" name="contenu" placeholder="Écrivez un message..." required
                            class="flex-1 px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 outline-none transition-all text-sm font-medium">
                        <button type="submit"
                            class="w-12 h-12 bg-purple-600 text-white rounded-xl flex items-center justify-center hover:bg-purple-700 transition-all shadow-lg shadow-purple-100">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Interview Evaluation Modal -->
    <div id="evaluationInterviewModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4 modal-backdrop">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Évaluation de l'entretien</h3>
                    <p id="eval_candidate_info" class="text-sm text-gray-500 font-medium"></p>
                </div>
                <button onclick="closeEvaluationInterviewModal()"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('recruteur.evaluations.store') }}" method="POST" class="p-8 space-y-8">
                @csrf
                <input type="hidden" name="entretien_id" id="eval_entretien_id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Compétences Techniques -->
                    <div class="space-y-3">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Compétences
                            Techniques</label>
                        <div class="flex gap-2">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="competences_techniques" value="{{ $i }}" class="hidden peer"
                                        required>
                                    <i
                                        class="fas fa-circle text-lg text-gray-200 peer-checked:text-purple-600 hover:text-purple-300 transition-colors"></i>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Communication -->
                    <div class="space-y-3">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Communication</label>
                        <div class="flex gap-2">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="communication" value="{{ $i }}" class="hidden peer" required>
                                    <i
                                        class="fas fa-circle text-lg text-gray-200 peer-checked:text-blue-600 hover:text-blue-300 transition-colors"></i>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Motivation -->
                    <div class="space-y-3">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Motivation</label>
                        <div class="flex gap-2">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="motivation" value="{{ $i }}" class="hidden peer" required>
                                    <i
                                        class="fas fa-circle text-lg text-gray-200 peer-checked:text-emerald-600 hover:text-emerald-300 transition-colors"></i>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Culture Fit -->
                    <div class="space-y-3">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Culture Fit</label>
                        <div class="flex gap-2">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="culture_fit" value="{{ $i }}" class="hidden peer" required>
                                    <i
                                        class="fas fa-circle text-lg text-gray-200 peer-checked:text-amber-600 hover:text-amber-300 transition-colors"></i>
                                </label>
                            @endfor
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Recommandation</label>
                    <select name="recommandation"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 outline-none font-bold text-sm"
                        required>
                        <option value="fortement_recommande">Fortement recommandé</option>
                        <option value="recommande">Recommandé</option>
                        <option value="neutre" selected>Neutre</option>
                        <option value="pas_recommande">Pas recommandé</option>
                        <option value="fortement_pas_recommande">Fortement pas recommandé</option>
                    </select>
                </div>

                <div class="space-y-3">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Commentaires détaillés</label>
                    <textarea name="commentaires" rows="5"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 outline-none font-medium text-sm resize-none"
                        placeholder="Décrivez les points forts et points faibles observés durant l'entretien..."></textarea>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="closeEvaluationInterviewModal()"
                        class="flex-1 py-4 bg-gray-50 text-gray-600 rounded-xl font-bold hover:bg-gray-100 transition-all">
                        Annuler
                    </button>
                    <button type="submit"
                        class="flex-[2] py-4 bg-gray-900 text-white rounded-xl font-bold hover:bg-black shadow-lg transition-all">
                        Finaliser l'évaluation
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Prévisualisation du logo
        document.querySelector('input[name="logo_entreprise"]').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const reader = new FileReader();
            const previewContainer = this.closest('.grid').querySelector('.overflow-hidden');

            reader.onload = function (e) {
                let img = previewContainer.querySelector('img');
                if (!img) {
                    previewContainer.innerHTML = '';
                    img = document.createElement('img');
                    img.className = 'w-full h-full object-cover';
                    previewContainer.appendChild(img);
                }
                img.src = e.target.result;
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection

@push('styles')
    <style>
        .tab-button.active {
            border-bottom-color: #7c3aed;
            color: #7c3aed;
        }

        .tab-content {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .offer-card,
        .application-card,
        .interview-card {
            transition: all 0.3s ease;
        }

        .offer-card:hover,
        .application-card:hover,
        .interview-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .modal-backdrop {
            backdrop-filter: blur(5px);
        }

        .calendar-day {
            transition: all 0.2s ease;
        }

        .calendar-day:hover {
            background-color: #f3f4f6;
            cursor: pointer;
        }

        .calendar-day.has-event {
            background-color: #ede9fe;
            color: #7c3aed;
            font-weight: 600;
        }

        .notification-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Tab switching
        function switchTab(tabName) {
            // Hide all tabs and contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-purple-500', 'text-purple-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab
            document.getElementById(tabName + '-content').classList.remove('hidden');
            const activeTab = document.getElementById(tabName + '-tab');
            activeTab.classList.add('active', 'border-purple-500', 'text-purple-600');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
        }

        // Modal functions
        function openOfferModal() {
            document.getElementById('offerModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeOfferModal() {
            document.getElementById('offerModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function openInterviewModal(candidatureId) {
            if (candidatureId) {
                document.getElementById('int_candidature_id').value = candidatureId;
            }
            document.getElementById('interviewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeInterviewModal() {
            document.getElementById('interviewModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Application functions
        function viewApplication(id, focusChat = false) {
            const application = @json($candidatures).find(a => a.id == id);
            if (!application) return;

            // Marquer comme lu (en_cours) si en_attente
            if (!application.statut || application.statut === 'en_attente') {
                fetch(`/recruteur/candidatures/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        application.statut = 'en_cours';
                        // Update UI
                        const newBadge = document.getElementById(`new-badge-${id}`);
                        if (newBadge) newBadge.style.display = 'none';
                        
                        const tabBadge = document.getElementById('tab-badge-applications');
                        if (tabBadge) {
                            let currentCount = parseInt(tabBadge.innerText) || 0;
                            let newCount = Math.max(0, currentCount - 1);
                            if (newCount === 0) {
                                tabBadge.style.display = 'none';
                            } else {
                                tabBadge.innerText = newCount;
                            }
                        }
                    }
                }).catch(err => console.error('Erreur lecture candidature', err));
            }

            document.getElementById('app_name').innerText = application.candidat.user.name;
            const avatarDiv = document.getElementById('app_avatar');
            if (application.candidat.user.profile_picture) {
                avatarDiv.innerHTML = `<img src="/storage/${application.candidat.user.profile_picture}" class="w-full h-full object-cover">`;
                avatarDiv.classList.add('overflow-hidden');
            } else {
                avatarDiv.innerText = application.candidat.user.name.charAt(0);
                avatarDiv.classList.remove('overflow-hidden');
            }
            document.getElementById('app_title').innerText = application.candidat.titre_professionnel || 'Candidat';
            document.getElementById('app_email').innerHTML = `<i class="fas fa-envelope mr-2"></i>${application.candidat.user.email}`;
            document.getElementById('app_date').innerHTML = `<i class="fas fa-clock mr-2"></i>Postulé le ${new Date(application.created_at).toLocaleDateString()}`;
            document.getElementById('app_score').innerText = (application.score_matching || 0) + '%';
            document.getElementById('app_dispo').innerText = application.disponibilite || application.candidat.disponibilite || 'Non renseignée';
            document.getElementById('app_salary').innerText = (application.pretention_salariale || application.candidat.pretention_salariale || 0) + ' €/an';
            document.getElementById('app_motivation').innerText = application.lettre_motivation || 'Aucune lettre de motivation fournie.';

            // Expériences
            const experiencesDiv = document.getElementById('app_experiences');
            if (application.candidat.experiences && application.candidat.experiences.length > 0) {
                const expHtml = application.candidat.experiences.sort((a, b) => new Date(b.date_debut) - new Date(a.date_debut)).map(exp => {
                    const dateFin = exp.poste_actuel ? 'Présent' : (exp.date_fin ? new Date(exp.date_fin).toLocaleDateString() : '');
                    return `
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex gap-4">
                            <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div>
                                <h6 class="font-bold text-gray-900">${exp.poste}</h6>
                                <p class="text-sm font-medium text-purple-600">${exp.entreprise} <span class="text-gray-400 font-normal">| ${exp.localisation}</span></p>
                                <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-widest">${new Date(exp.date_debut).toLocaleDateString()} - ${dateFin}</p>
                                ${exp.description ? `<p class="text-xs text-gray-600 mt-2 leading-relaxed">${exp.description}</p>` : ''}
                            </div>
                        </div>
                    `;
                }).join('');
                experiencesDiv.innerHTML = expHtml;
            } else {
                experiencesDiv.innerHTML = '<p class="text-sm text-gray-500 italic">Aucune expérience renseignée.</p>';
            }

            // Formations
            const formationsDiv = document.getElementById('app_formations');
            if (application.candidat.formations && application.candidat.formations.length > 0) {
                const formHtml = application.candidat.formations.sort((a, b) => new Date(b.date_debut) - new Date(a.date_debut)).map(form => {
                    const dateFin = form.en_cours ? 'En cours' : (form.date_fin ? new Date(form.date_fin).toLocaleDateString() : '');
                    return `
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex gap-4">
                            <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div>
                                <h6 class="font-bold text-gray-900">${form.diplome}</h6>
                                <p class="text-sm font-medium text-indigo-600">${form.etablissement} <span class="text-gray-400 font-normal">| ${form.localisation}</span></p>
                                <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-widest">${new Date(form.date_debut).toLocaleDateString()} - ${dateFin}</p>
                                ${form.description ? `<p class="text-xs text-gray-600 mt-2 leading-relaxed">${form.description}</p>` : ''}
                            </div>
                        </div>
                    `;
                }).join('');
                formationsDiv.innerHTML = formHtml;
            } else {
                formationsDiv.innerHTML = '<p class="text-sm text-gray-500 italic">Aucune formation renseignée.</p>';
            }

            // Gestion du CV
            const cvPathRaw = application.cv_path || application.candidat.cv_path;
            const cvPath = cvPathRaw ? `/storage/${cvPathRaw}` : null;
            const iframe = document.getElementById('app_cv_iframe');
            const noPreview = document.getElementById('cv_no_preview');
            const downloadLink = document.getElementById('app_cv_download');
            const newTabLink = document.getElementById('app_cv_new_tab');
            const loading = document.getElementById('cv_loading');

            if (loading) loading.style.display = 'flex';

            if (cvPath) {
                const fullUrl = window.location.origin + cvPath;
                downloadLink.href = cvPath;
                newTabLink.href = cvPath;
                downloadLink.classList.remove('hidden');
                newTabLink.classList.remove('hidden');

                if (cvPath.toLowerCase().endsWith('.pdf')) {
                    iframe.src = cvPath;
                    iframe.classList.remove('hidden');
                    noPreview.classList.add('hidden');
                } else if (cvPath.toLowerCase().endsWith('.doc') || cvPath.toLowerCase().endsWith('.docx')) {
                    // Utilisation du Google Docs Viewer pour les fichiers Word
                    iframe.src = `https://docs.google.com/gview?url=${encodeURIComponent(fullUrl)}&embedded=true`;
                    iframe.classList.remove('hidden');
                    noPreview.classList.add('hidden');
                } else {
                    iframe.classList.add('hidden');
                    noPreview.classList.remove('hidden');
                    noPreview.querySelector('p').innerText = "Format non prévisualisable";
                }

                iframe.onload = () => { if (loading) loading.style.display = 'none'; };
                // Timeout au cas où l'iframe ne charge pas (ex: bloqué)
                setTimeout(() => { if (loading) loading.style.display = 'none'; }, 3000);
            } else {
                downloadLink.classList.add('hidden');
                newTabLink.classList.add('hidden');
                iframe.classList.add('hidden');
                noPreview.classList.remove('hidden');
                if (loading) loading.style.display = 'none';
            }

            // Populate Chat
            document.getElementById('app_chat_candidature_id').value = id;
            renderAppChat(id);

            document.getElementById('app_action_btn').onclick = () => {
                // Focus chat by default when clicking action
                document.querySelector('#app-chat-form input[name="contenu"]').focus();
            };

            document.getElementById('applicationDetailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            if (focusChat) {
                setTimeout(() => {
                    document.querySelector('#app-chat-form input[name="contenu"]').focus();
                }, 300);
            }
        }

        function renderAppChat(candidatureId) {
            const chatMessages = document.getElementById('app_chat_messages');
            chatMessages.innerHTML = '';

            const msgs = allMessages.filter(m => m.candidature_id == candidatureId);
            const sortedMsgs = [...msgs].sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

            if (sortedMsgs.length === 0) {
                chatMessages.innerHTML = `
                            <div class="text-center py-12 text-gray-400">
                                <i class="fas fa-comments text-3xl mb-2 opacity-20"></i>
                                <p class="text-xs font-bold uppercase tracking-widest">Aucun message</p>
                            </div>
                        `;
            } else {
                sortedMsgs.forEach(msg => {
                    const isMine = msg.expediteur_id == {{ Auth::id() }};
                    const date = new Date(msg.created_at);
                    const timeString = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                    const msgHtml = `
                                <div class="flex flex-col ${isMine ? 'items-end' : 'items-start'}">
                                    <div class="max-w-[85%] ${isMine ? 'bg-purple-600 text-white rounded-l-xl rounded-tr-xl' : 'bg-white border border-gray-100 text-gray-700 rounded-r-xl rounded-tl-xl'} p-3 shadow-sm">
                                        <p class="text-xs font-medium leading-relaxed">${msg.contenu}</p>
                                    </div>
                                    <span class="text-[9px] font-bold text-gray-400 mt-1 mx-1 uppercase tracking-widest">${timeString}</span>
                                </div>
                            `;
                    chatMessages.innerHTML += msgHtml;
                });
            }
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function handleAppChatSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalHtml = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch("{{ route('recruteur.messages.store') }}", {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': data._token
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allMessages.push(data.message);
                        renderAppChat(data.message.candidature_id);
                        form.reset();
                    } else {
                        alert('Erreur: ' + (data.message || 'Erreur inconnue'));
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Erreur lors de l\'envoi : ' + err.message);
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHtml;
                });
        }

        function closeApplicationModal() {
            document.getElementById('applicationDetailModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function openEvaluationInterviewModal(entretienId, candidateName, jobTitle) {
            document.getElementById('eval_entretien_id').value = entretienId;
            document.getElementById('eval_candidate_info').innerText = `${candidateName} - ${jobTitle}`;
            document.getElementById('evaluationInterviewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEvaluationInterviewModal() {
            document.getElementById('evaluationInterviewModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function scheduleInterview(candidateId) {
            openInterviewModal();
            // Pre-fill modal with candidate data
        }

        // Toast notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-20 right-4 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 z-50 ${type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                    type === 'warning' ? 'bg-yellow-500 text-white' :
                        'bg-blue-500 text-white'
                }`;

            toast.innerHTML = `
                                <div class="flex items-center space-x-2">
                                    <i class="fas ${type === 'success' ? 'fa-check-circle' :
                    type === 'error' ? 'fa-exclamation-circle' :
                        type === 'warning' ? 'fa-exclamation-triangle' :
                            'fa-info-circle'
                }"></i>
                                    <span>${message}</span>
                                </div>
                            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Offer form submission - Simplified version
        document.getElementById('offerForm')?.addEventListener('submit', function (e) {
            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Publication en cours...';

            // Let the form submit normally
            setTimeout(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }, 3000);
        });

        // Alternative form submission method for network errors
        function submitFormAlternative(form, submitButton, originalText) {
            console.log('Using alternative submission method...');

            // Convert FormData to URL-encoded string
            const formData = new FormData(form);
            const params = new URLSearchParams();

            for (let [key, value] of formData.entries()) {
                params.append(key, value);
            }

            // Create XMLHttpRequest as fallback
            const xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function () {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;

                console.log('XHR Response status:', xhr.status);

                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        console.log('XHR Response data:', data);

                        if (data.success) {
                            closeOfferModal();
                            showToast(data.message, 'success');
                            form.reset();
                            addNewOffer(data.offre);
                        } else {
                            showToast(data.message || 'Erreur lors de la publication', 'error');
                        }
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        showToast('Erreur lors du traitement de la réponse', 'error');
                    }
                } else {
                    showToast('Erreur HTTP ' + xhr.status + ' lors de la publication', 'error');
                }
            };

            xhr.onerror = function () {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
                console.error('XHR Network error');
                showToast('Erreur réseau persistante. Veuillez réessayer plus tard.', 'error');
            };

            xhr.onabort = function () {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
                showToast('Publication annulée', 'info');
            };

            try {
                xhr.send(params.toString());
            } catch (error) {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
                console.error('XHR Send error:', error);
                showToast('Erreur lors de l\'envoi des données', 'error');
            }
        }

        // Les fonctions openInterviewModal et closeInterviewModal sont définies plus bas

        // Edit offer functions
        function openEditOfferModal(offreId, titre, typeContrat, lieu, salaireMin, salaireMax, description, competences, statut, experienceReq, teletravail, nbPostes, dateLimite) {
            document.getElementById('edit_offre_id').value = offreId;
            document.getElementById('edit_titre').value = titre;
            document.getElementById('edit_type_contrat').value = typeContrat;
            document.getElementById('edit_lieu').value = lieu;
            document.getElementById('edit_salaire_min').value = salaireMin;
            document.getElementById('edit_salaire_max').value = salaireMax;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_statut').value = statut;
            document.getElementById('edit_nb_postes').value = nbPostes || 1;
            document.getElementById('edit_date_limite').value = dateLimite || '';

            // Enum selects
            const expSel = document.getElementById('edit_experience_req');
            if (expSel) expSel.value = experienceReq || 'debutant';
            const telSel = document.getElementById('edit_teletravail');
            if (telSel) telSel.value = teletravail || 'non';

            // Compétences JSON → tags
            const editTagsContainer = document.getElementById('editCompetencesList');
            const editHidden = document.getElementById('edit_competences_req');
            editTagsContainer.innerHTML = '';
            editCompetencesArray = [];
            try {
                let parsed = typeof competences === 'string' ? JSON.parse(competences) : competences;
                if (Array.isArray(parsed)) {
                    parsed.forEach(c => addEditCompetenceTag(c));
                } else if (typeof competences === 'string' && competences.trim()) {
                    competences.split(',').map(s => s.trim()).filter(Boolean).forEach(c => addEditCompetenceTag(c));
                }
            } catch (e) {
                if (typeof competences === 'string' && competences.trim()) {
                    competences.split(',').map(s => s.trim()).filter(Boolean).forEach(c => addEditCompetenceTag(c));
                }
            }
            if (editHidden) editHidden.value = JSON.stringify(editCompetencesArray);

            document.getElementById('editOfferModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditOfferModal() {
            document.getElementById('editOfferModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('editOfferForm').reset();
        }

        function deleteOffer(offreId) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')) {
                return;
            }

            fetch(`/offres/${offreId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the offer card from DOM
                        const offreCard = document.querySelector(`[data-offre-id="${offreId}"]`);
                        if (offreCard) {
                            offreCard.remove();
                        }
                        showToast('Offre supprimée avec succès!', 'success');
                        // Reload page to update stats
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast(data.message || 'Erreur lors de la suppression', 'error');
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    showToast('Erreur réseau lors de la suppression', 'error');
                });
        }

        // Edit offer form submission — unique handler (action + compétences + spinner)
        document.getElementById('editOfferForm')?.addEventListener('submit', function (e) {
            // 1. Sérialiser les compétences en JSON avant soumission
            const hiddenComp = document.getElementById('edit_competences_req');
            if (hiddenComp) hiddenComp.value = JSON.stringify(editCompetencesArray);

            // 2. Fixer l'URL d'action avec l'ID de l'offre (contrainte migration : route /offres/{id})
            const offreId = document.getElementById('edit_offre_id').value;
            if (!offreId) { e.preventDefault(); alert('ID offre manquant.'); return; }
            this.action = `/offres/${offreId}`;

            // 3. Validation client : salaire_min <= salaire_max
            const sMin = parseFloat(document.getElementById('edit_salaire_min').value);
            const sMax = parseFloat(document.getElementById('edit_salaire_max').value);
            if (!isNaN(sMin) && !isNaN(sMax) && sMin > sMax) {
                e.preventDefault();
                alert('Le salaire minimum ne peut pas dépasser le salaire maximum.');
                return;
            }

            // 4. Spinner
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mise à jour...';
        });

        // ─── Compétences : formulaire modification ────────────────────────────────────
        let editCompetencesArray = [];

        function addEditCompetence() {
            const input = document.getElementById('editCompetenceInput');
            const val = input.value.trim();
            if (!val) return;
            if (editCompetencesArray.includes(val)) { input.value = ''; return; }
            editCompetencesArray.push(val);
            renderEditTags();
            input.value = '';
            document.getElementById('edit_competences_req').value = JSON.stringify(editCompetencesArray);
        }

        function removeEditCompetence(val) {
            editCompetencesArray = editCompetencesArray.filter(c => c !== val);
            renderEditTags();
            document.getElementById('edit_competences_req').value = JSON.stringify(editCompetencesArray);
        }

        function renderEditTags() {
            const container = document.getElementById('editCompetencesList');
            if (!container) return;
            container.innerHTML = editCompetencesArray.map(c =>
                `<span class="flex items-center gap-1 px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm">
                                    ${c}
                                    <button type="button" onclick="removeEditCompetence('${c}')" class="ml-1 text-purple-400 hover:text-purple-700">&times;</button>
                                </span>`
            ).join('');
        }

        function addEditCompetenceTag(val) {
            if (!editCompetencesArray.includes(val)) {
                editCompetencesArray.push(val);
                renderEditTags();
            }
        }

        // Dynamic content addition
        function addNewOffer(offre = null) {
            const offersList = document.getElementById('offers-list');
            const newOffer = document.createElement('div');
            newOffer.className = 'bg-gray-50 rounded-lg p-6 hover:shadow-md transition-all duration-200 border border-gray-200';

            // Use real data if available, otherwise use placeholder
            const titre = offre?.titre || 'Nouvelle offre publiée';
            const lieu = offre?.lieu || 'Paris';
            const typeContrat = offre?.type_contrat || 'CDI';
            const description = offre?.description || 'Description de la nouvelle offre...';
            const salaireMin = offre?.salaire_min || '45K';
            const salaireMax = offre?.salaire_max || '65K';

            newOffer.innerHTML = `
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h4 class="text-lg font-semibold text-gray-900">${titre}</h4>
                                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Active</span>
                                        </div>
                                        <p class="text-gray-600 mb-3">${description}</p>
                                        <div class="flex items-center space-x-6 text-sm text-gray-500">
                                            <span><i class="fas fa-map-marker-alt mr-1"></i>${lieu}</span>
                                            <span><i class="fas fa-briefcase mr-1"></i>${typeContrat}</span>
                                            <span><i class="fas fa-euro-sign mr-1"></i>${salaireMin}-${salaireMax}K</span>
                                            <span><i class="fas fa-users mr-1"></i>0 candidat</span>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2 ml-4">
                                        <button onclick="openEditOfferModal(${offre?.id || 0}, '${titre.replace(/'/g, "\\'")}', '${typeContrat}', '${lieu.replace(/'/g, "\\'")}', '${salaireMin}', '${salaireMax}', '${description.replace(/'/g, "\\'")}', [], 'publie', 'debutant', 'non', 1, '')" 
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="deleteOffer(${offre?.id || 0})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            `;

            offersList.insertBefore(newOffer, offersList.firstChild);

            // Animate in
            newOffer.style.opacity = '0';
            newOffer.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                newOffer.style.transition = 'all 0.3s ease';
                newOffer.style.opacity = '1';
                newOffer.style.transform = 'translateY(0)';
            }, 100);
        }

        function addNewInterview() {
            const interviewsList = document.querySelector('#interviews-content .space-y-4');
            const newInterview = document.createElement('div');
            newInterview.className = 'bg-white border border-gray-200 rounded-lg p-6';
            newInterview.innerHTML = `
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h4 class="text-lg font-semibold">Nouvel entretien planifié</h4>
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">À venir</span>
                                        </div>
                                        <p class="text-gray-600 mb-2">Poste à définir</p>
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <span><i class="fas fa-calendar mr-1"></i>Date sélectionnée</span>
                                            <span><i class="fas fa-clock mr-1"></i>Heure sélectionnée</span>
                                            <span><i class="fas fa-video mr-1"></i>Visioconférence</span>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-video mr-2"></i>Rejoindre
                                        </button>
                                        <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-edit mr-2"></i>Modifier
                                        </button>
                                    </div>
                                </div>
                            `;

            interviewsList.insertBefore(newInterview, interviewsList.firstChild);

            // Animate in
            newInterview.style.opacity = '0';
            newInterview.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                newInterview.style.transition = 'all 0.3s ease';
                newInterview.style.opacity = '1';
                newInterview.style.transform = 'translateY(0)';
            }, 100);
        }

        // ─── Compétences : formulaire création ────────────────────────────────────────
        let createCompetencesArray = [];

        function addCompetence() {
            const input = document.getElementById('competenceInput');
            const val = input.value.trim();
            if (!val) return;
            if (createCompetencesArray.includes(val)) { input.value = ''; return; }
            createCompetencesArray.push(val);
            renderCreateTags();
            input.value = '';
            document.getElementById('competences_req').value = JSON.stringify(createCompetencesArray);
        }

        function removeCreateCompetence(val) {
            createCompetencesArray = createCompetencesArray.filter(c => c !== val);
            renderCreateTags();
            document.getElementById('competences_req').value = JSON.stringify(createCompetencesArray);
        }

        function renderCreateTags() {
            const container = document.getElementById('competencesList');
            container.innerHTML = createCompetencesArray.map(c =>
                `<span class="flex items-center gap-1 px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm">
                                    ${c}
                                    <button type="button" onclick="removeCreateCompetence('${c}')" class="ml-1 text-purple-400 hover:text-purple-700">&times;</button>
                                </span>`
            ).join('');
        }

        document.getElementById('competenceInput')?.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); addCompetence(); }
        });

        document.getElementById('offerForm')?.addEventListener('submit', function () {
            document.getElementById('competences_req').value = JSON.stringify(createCompetencesArray);
        });



        // Search functionality
        document.querySelector('input[placeholder="Rechercher..."]')?.addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase();
            const offerCards = document.querySelectorAll('#offers-list > div');

            offerCards.forEach(card => {
                const title = card.querySelector('h4').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();

                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Initialize dashboard on load
        document.addEventListener('DOMContentLoaded', function () {
            // Animate stats on load
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.animation = 'slideUp 0.5s ease-out';
                }, index * 100);
            });

            // Auto-refresh data every 30 seconds
            setInterval(() => {
                console.log('Refreshing dashboard data...');
                // Here you would typically fetch fresh data from your API
            }, 30000);
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            // Ctrl/Cmd + N for new offer
            if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                e.preventDefault();
                openOfferModal();
            }

            // Escape to close modals
            if (e.key === 'Escape') {
                closeOfferModal();
                closeInterviewModal();
                closeMessageModal();
            }
        });

        function openMessageModal(candidatureId, candidateName) {
            document.getElementById('msg_candidature_id').value = candidatureId;
            document.getElementById('candidateName').innerText = candidateName;
            document.getElementById('messageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMessageModal() {
            document.getElementById('messageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('messageForm').reset();
        }

        function openInterviewModal(candidatureId = null, candidateName = null, jobTitle = null) {
            const select = document.getElementById('int_candidature_id');
            if (candidatureId) {
                select.value = candidatureId;
            } else {
                select.value = "";
            }
            document.getElementById('interviewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            toggleLienVisio(); // Initial state
        }

        function closeInterviewModal() {
            document.getElementById('interviewModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('interviewForm').reset();
        }

        function toggleLienVisio() {
            const type = document.getElementById('int_type').value;
            const visioContainer = document.getElementById('visio_container');
            const lienInput = document.getElementById('lien_visio');

            if (type === 'visio') {
                visioContainer.classList.remove('hidden');
                lienInput.required = true;
            } else {
                visioContainer.classList.add('hidden');
                lienInput.required = false;
            }
        }

        // Initialisation des graphiques
        document.addEventListener('DOMContentLoaded', function () {
            // Graphique des candidatures (Simulation)
            const ctxApp = document.getElementById('applicationsChart')?.getContext('2d');
            if (ctxApp) {
                new Chart(ctxApp, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($monthsLabels) !!},
                        datasets: [{
                            label: 'Candidatures',
                            data: {!! json_encode($applicationsData) !!},
                            borderColor: '#7c3aed',
                            backgroundColor: 'rgba(124, 58, 237, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }

            // Graphique de répartition (Simulation)
            const ctxStatus = document.getElementById('statusChart')?.getContext('2d');
            if (ctxStatus) {
                new Chart(ctxStatus, {
                    type: 'doughnut',
                    data: {
                        labels: ['En attente', 'En cours', 'Accepté', 'Refusé'],
                        datasets: [{
                            data: {!! json_encode($candidaturesStatus) !!},
                            backgroundColor: ['#3b82f6', '#f59e0b', '#10b981', '#ef4444'],
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            }
        });

        // Gestion de la Messagerie
        let allMessages = @json($messages);

        function openChat(candidatureId) {
            document.getElementById('no-chat-selected').classList.add('hidden');
            const chatWindow = document.getElementById('chat-window');
            chatWindow.classList.remove('hidden');
            chatWindow.classList.add('flex');

            const destInput = document.getElementById('chat-destinataire-id');
            const candInput = document.getElementById('chat-candidature-id');

            let msgs = [];
            let correspondentName = 'Utilisateur';
            let offerTitle = 'Discussion directe';

            if (typeof candidatureId === 'string' && candidatureId.startsWith('null-')) {
                const userId = candidatureId.split('-')[1];
                candInput.value = '';
                destInput.value = userId;
                msgs = allMessages.filter(m =>
                    (m.expediteur_id == userId || m.destinataire_id == userId) && !m.candidature_id
                );
                if (msgs.length > 0) {
                    const sample = msgs[0];
                    const other = sample.expediteur_id == {{ Auth::id() }} ? sample.destinataire : sample.expediteur;
                    correspondentName = other.name;
                }
            } else {
                candInput.value = candidatureId;
                destInput.value = '';
                msgs = allMessages.filter(m => m.candidature_id == candidatureId);

                if (msgs.length > 0) {
                    const sample = msgs[0];
                    correspondentName = sample.candidature?.candidat?.user?.name || 'Candidat';
                    offerTitle = sample.candidature?.offre?.titre || 'Offre';
                    destInput.value = sample.expediteur_id == {{ Auth::id() }} ? sample.destinataire_id : sample.expediteur_id;
                } else {
                    // Si aucun message n'existe encore, on cherche dans la liste des candidatures injectée
                    const application = @json($candidatures).find(a => a.id == candidatureId);
                    if (application) {
                        correspondentName = application.candidat.user.name;
                        offerTitle = application.offre.titre;
                        destInput.value = application.candidat.user_id;
                    }
                }
            }

            document.getElementById('chat-user-name').innerText = correspondentName;
            document.getElementById('chat-offer-title').innerText = offerTitle;
            document.getElementById('chat-user-avatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(correspondentName)}&background=6366f1&color=fff`;

            const chatMessages = document.getElementById('chat-messages');
            chatMessages.innerHTML = '';

            if (msgs.length > 0) {
                const sortedMsgs = [...msgs].sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

                sortedMsgs.forEach(msg => {
                    const isMine = msg.expediteur_id == {{ Auth::id() }};
                    const date = new Date(msg.created_at);
                    const timeString = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                    const msgHtml = `
                            <div class="flex flex-col ${isMine ? 'items-end' : 'items-start'}">
                                <div class="max-w-[70%] ${isMine ? 'bg-indigo-600 text-white rounded-l-2xl rounded-tr-2xl' : 'bg-white border border-slate-100 text-slate-700 rounded-r-2xl rounded-tl-2xl'} p-4 shadow-sm">
                                    <p class="text-sm font-medium leading-relaxed">${msg.contenu}</p>
                                </div>
                                <span class="text-[10px] font-bold text-slate-400 mt-2 mx-1 uppercase tracking-widest">${timeString}</span>
                            </div>
                        `;
                    chatMessages.innerHTML += msgHtml;
                });

                chatMessages.scrollTop = chatMessages.scrollHeight;

                // Marquer comme lu
                const otherUserId = destInput.value;
                const unreadList = msgs.filter(m => m.destinataire_id == {{ Auth::id() }} && !m.lu_at);
                if (otherUserId && unreadList.length > 0) {
                    fetch(`/messages/${otherUserId}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            msgs.forEach(m => {
                                if (m.destinataire_id == {{ Auth::id() }} && !m.lu_at) {
                                    m.lu_at = new Date().toISOString();
                                }
                            });
                            // Masquer le badge global ou liste si présent (si applicable)
                            const badge = document.querySelector(`.chat-item-${otherUserId} .bg-rose-500`);
                            if (badge) badge.style.display = 'none';

                            // Mettre à jour les compteurs globaux
                            const statCard = document.getElementById('stat-unread-messages-recruteur');
                            if (statCard) {
                                let currentCount = parseInt(statCard.innerText) || 0;
                                let newCount = Math.max(0, currentCount - unreadList.length);
                                statCard.innerText = newCount;
                                
                                const tabBadge = document.getElementById('tab-badge-messages-recruteur');
                                if (tabBadge) {
                                    if (newCount === 0) {
                                        tabBadge.style.display = 'none';
                                    } else {
                                        tabBadge.innerText = newCount;
                                    }
                                }
                            }
                        }
                    }).catch(err => console.error('Erreur lecture', err));
                }
            } else {
                chatMessages.innerHTML = `
                        <div class="flex flex-col items-center justify-center h-full text-slate-400 p-8 text-center">
                            <i class="fas fa-paper-plane text-3xl mb-4 opacity-20"></i>
                            <p class="text-sm font-medium">Envoyez votre premier message pour démarrer la discussion.</p>
                        </div>
                    `;
            }
        }
        document.getElementById('chat-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnHtml = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            submitBtn.disabled = true;

            fetch(form.action, {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': data._token
                }
            })
                .then(async response => {
                    const resData = await response.json();
                    if (!response.ok) {
                        throw new Error(resData.message || 'Erreur serveur');
                    }
                    return resData;
                })
                .then(data => {
                    if (data.success) {
                        const chatMessages = document.getElementById('chat-messages');
                        const msg = data.message;
                        const date = new Date(msg.created_at);
                        const timeString = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                        const msgHtml = `
                                <div class="flex flex-col items-end">
                                    <div class="max-w-[70%] bg-indigo-600 text-white rounded-l-2xl rounded-tr-2xl p-4 shadow-sm">
                                        <p class="text-sm font-medium leading-relaxed">${msg.contenu}</p>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400 mt-2 mx-1 uppercase tracking-widest">${timeString}</span>
                                </div>
                            `;

                        chatMessages.insertAdjacentHTML('beforeend', msgHtml);
                        chatMessages.scrollTop = chatMessages.scrollHeight;

                        form.querySelector('input[name="contenu"]').value = '';

                        if (typeof allMessages !== 'undefined') {
                            allMessages.push(msg);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur: ' + error.message);
                })
                .finally(() => {
                    submitBtn.innerHTML = originalBtnHtml;
                    submitBtn.disabled = false;
                });
        });
    </script>
@endpush