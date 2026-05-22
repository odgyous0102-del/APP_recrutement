@extends('layouts.app')

@section('title', 'Mon Espace Candidat')

@section('content')
    <div class="min-h-screen bg-slate-50 flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-slate-200 hidden md:flex flex-col sticky top-0 h-screen">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <span class="font-bold text-slate-800 text-lg tracking-tight">CandidatPro</span>
                </div>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <button onclick="switchTab('overview')" id="nav-overview"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 nav-link active-nav">
                    <i class="fas fa-th-large text-lg"></i>
                    <span class="font-medium">Tableau de bord</span>
                </button>
                <button onclick="switchTab('jobs')" id="nav-jobs"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 nav-link text-slate-500 hover:bg-slate-50">
                    <i class="fas fa-search text-lg"></i>
                    <span class="font-medium">Trouver un job</span>
                </button>
                <button onclick="switchTab('applications')" id="nav-applications"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 nav-link text-slate-500 hover:bg-slate-50">
                    <i class="fas fa-file-alt text-lg"></i>
                    <span class="font-medium">Mes candidatures</span>
                </button>
                <button onclick="switchTab('interviews')" id="nav-interviews"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 nav-link text-slate-500 hover:bg-slate-50">
                    <i class="fas fa-calendar-alt text-lg"></i>
                    <span class="font-medium">Mes entretiens</span>
                    @if($entretiens->where('date_entretien', '>', now())->count() > 0)
                        <span class="ml-auto bg-amber-100 text-amber-600 text-[10px] font-black px-2 py-0.5 rounded-full">
                            {{ $entretiens->where('date_entretien', '>', now())->count() }}
                        </span>
                    @endif
                </button>
                <button onclick="switchTab('favoris')" id="nav-favoris"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 nav-link text-slate-500 hover:bg-slate-50">
                    <i class="fas fa-heart text-lg"></i>
                    <span class="font-medium">Mes Favoris</span>
                    @if(count($favorisIds) > 0)
                        <span class="ml-auto bg-rose-100 text-rose-600 text-[10px] font-black px-2 py-0.5 rounded-full">
                            {{ count($favorisIds) }}
                        </span>
                    @endif
                </button>
                <button onclick="switchTab('messages')" id="nav-messages"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 nav-link text-slate-500 hover:bg-slate-50 relative">
                    <i class="fas fa-envelope text-lg"></i>
                    <span class="font-medium">Messages</span>
                    @if($messages->where('lu_at', null)->where('destinataire_id', Auth::id())->count() > 0)
                        <span id="tab-badge-messages" class="absolute right-4 w-2 h-2 bg-rose-500 rounded-full"></span>
                    @endif
                </button>
                <button onclick="switchTab('profile')" id="nav-profile"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 nav-link text-slate-500 hover:bg-slate-50">
                    <i class="fas fa-user-circle text-lg"></i>
                    <span class="font-medium">Mon Profil</span>
                </button>
            </nav>

            <div class="p-4 border-t border-slate-100">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-rose-500 hover:bg-rose-50 transition-all duration-200">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="font-medium">Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Top Bar -->
            <header
                class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-10 px-10 py-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-1.5 h-8 bg-indigo-600 rounded-full"></div>
                    <h2 id="current-tab-title" class="text-2xl font-black text-slate-900 tracking-tight">Tableau de bord</h2>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-black text-slate-800 uppercase tracking-widest">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-widest mt-0.5">
                            {{ $candidat->titre_professionnel ?? 'Candidat' }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 border-2 border-white shadow-xl shadow-indigo-100 overflow-hidden group cursor-pointer">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff"
                            alt="Avatar" class="group-hover:scale-110 transition-transform duration-300">
                    </div>
                </div>
            </header>

            <div class="p-10 space-y-10 animate__animated animate__fadeIn">
                {{-- Welcome Banner --}}
                <div id="tab-overview-banner" class="tab-pane">
                    <div class="bg-gradient-to-br from-indigo-600 to-indigo-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-indigo-200">
                        <div class="relative z-10">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border border-white/10">Mon Profil</span>
                                <span class="text-xs font-bold text-indigo-100">Prêt pour de nouvelles opportunités ?</span>
                            </div>
                            <h2 class="text-4xl font-black mb-2 tracking-tight">Ravi de vous revoir, {{ explode(' ', Auth::user()->name)[0] }} !</h2>
                            <p class="text-indigo-100 text-lg font-medium max-w-xl opacity-90">Découvrez les offres qui correspondent à votre profil et suivez l'avancement de vos candidatures en temps réel.</p>
                            <div class="mt-8 flex space-x-4">
                                <button onclick="switchTab('jobs')" class="bg-white text-indigo-600 px-8 py-4 rounded-2xl font-black hover:bg-indigo-50 transition shadow-lg flex items-center gap-2">
                                    <i class="fas fa-search"></i> Parcourir les offres
                                </button>
                                <button onclick="switchTab('profile')" class="bg-indigo-500/30 backdrop-blur-md text-white border border-white/20 px-8 py-4 rounded-2xl font-black hover:bg-indigo-500/50 transition flex items-center gap-2">
                                    <i class="fas fa-user-edit"></i> Compléter mon profil
                                </button>
                            </div>
                        </div>
                        {{-- Decorative --}}
                        <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
                        <div class="absolute left-1/2 bottom-0 w-64 h-64 bg-indigo-400/20 rounded-full blur-3xl"></div>
                    </div>
                </div>

                <!-- Tabs -->
                <div id="tab-overview" class="tab-pane space-y-10">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Stat Card -->
                        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group cursor-pointer"
                             onclick="switchTab('applications')">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-inner">
                                    <i class="fas fa-paper-plane text-2xl"></i>
                                </div>
                                <span class="text-[10px] font-black text-indigo-400 bg-indigo-50 px-3 py-1 rounded-full uppercase">Envoyées</span>
                            </div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Candidatures</p>
                            <h3 class="text-4xl font-black text-slate-900 leading-none">{{ $candidatures->count() }}</h3>
                        </div>
                        <!-- Stat Card -->
                        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group cursor-pointer"
                             onclick="switchTab('messages')">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-inner">
                                    <i class="fas fa-comments text-2xl"></i>
                                </div>
                                <span class="text-[10px] font-black text-emerald-400 bg-emerald-50 px-3 py-1 rounded-full uppercase">Contact</span>
                            </div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Non lus</p>
                            <h3 id="stat-unread-messages" class="text-4xl font-black text-slate-900 leading-none">
                                {{ $messages->where('destinataire_id', Auth::id())->whereNull('lu_at')->count() }}
                            </h3>
                        </div>
                        <!-- Stat Card -->
                        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group cursor-pointer"
                             onclick="switchTab('jobs')">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-inner">
                                    <i class="fas fa-check-circle text-2xl"></i>
                                </div>
                                <span class="text-[10px] font-black text-amber-400 bg-amber-50 px-3 py-1 rounded-full uppercase">Match</span>
                            </div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Offres ciblées</p>
                            <h3 class="text-4xl font-black text-slate-900 leading-none">{{ $offres->count() }}</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Section Entretiens (Nouveau) -->
                        <section class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-slate-50 flex items-center justify-between bg-amber-50/30">
                                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                    <i class="fas fa-calendar-check text-amber-500"></i> Entretiens à venir
                                </h3>
                                <button onclick="switchTab('interviews')"
                                    class="text-indigo-600 text-sm font-bold hover:underline">Voir tout</button>
                            </div>
                            <div class="divide-y divide-slate-50">
                                @forelse($entretiens->where('date_entretien', '>', now())->take(3) as $int)
                                    <div class="p-6 flex items-center gap-4 hover:bg-slate-50 transition-all">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-amber-100 flex flex-col items-center justify-center text-amber-700">
                                            <span
                                                class="text-[10px] font-black uppercase leading-none">{{ $int->date_entretien->format('M') }}</span>
                                            <span
                                                class="text-xl font-bold leading-none">{{ $int->date_entretien->format('d') }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-slate-800">
                                                {{ $int->candidature && $int->candidature->offre ? $int->candidature->offre->titre : 'Poste non défini' }}
                                            </h4>
                                            <div class="flex items-center gap-3 mt-1 text-xs text-slate-500">
                                                <span><i
                                                        class="fas fa-clock mr-1"></i>{{ $int->date_entretien->format('H:i') }}</span>
                                                <span><i class="fas fa-video mr-1"></i>{{ ucfirst($int->type) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            @if($int->statut == 'planifie')
                                                <form action="{{ route('candidat.entretiens.confirmer', $int->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition-all shadow-sm flex items-center gap-2">
                                                        <i class="fas fa-check"></i> Confirmer
                                                    </button>
                                                </form>
                                                <form action="{{ route('entretiens.annuler', $int->id) }}" method="POST"
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cet entretien ?')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="p-2 bg-rose-50 text-rose-500 rounded-lg text-xs hover:bg-rose-500 hover:text-white transition-all shadow-sm"
                                                        title="Annuler l'entretien">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @elseif($int->type == 'visio' && $int->lien_visio)
                                                <a href="{{ $int->lien_visio }}" target="_blank"
                                                    class="p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm"
                                                    title="Rejoindre l'entretien">
                                                    <i class="fas fa-video"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-12 text-center text-slate-400">
                                        <i class="fas fa-calendar-times text-4xl mb-3 block opacity-20"></i>
                                        <p>Aucun entretien prévu prochainement.</p>
                                    </div>
                                @endforelse
                            </div>
                        </section>

                        <section class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
                                <h3 class="font-bold text-slate-800">Candidatures récentes</h3>
                                <button onclick="switchTab('applications')"
                                    class="text-indigo-600 text-sm font-bold hover:underline">Voir tout</button>
                            </div>
                            <div class="divide-y divide-slate-50">
                                @forelse($candidatures->take(3) as $app)
                                    <div class="p-6 flex items-center gap-4 hover:bg-slate-50 transition-all">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                                            <i class="fas fa-building text-xl"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-slate-800">{{ $app->offre->titre }}</h4>
                                            <p class="text-sm text-slate-500">{{ $app->offre->recruteur->nom_entreprise }}</p>
                                        </div>
                                        <div class="text-right">
                                            @php
                                                $statusClass = [
                                                    'en_attente' => 'bg-slate-100 text-slate-600',
                                                    'en_cours' => 'bg-indigo-100 text-indigo-600',
                                                    'acceptee' => 'bg-emerald-100 text-emerald-600',
                                                    'refusee' => 'bg-rose-100 text-rose-600'
                                                ][$app->statut] ?? 'bg-slate-100 text-slate-600';
                                            @endphp
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $statusClass }}">
                                                {{ str_replace('_', ' ', $app->statut) }}
                                            </span>
                                            <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold">
                                                {{ $app->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-12 text-center text-slate-400">
                                        <p>Aucune candidature envoyée.</p>
                                    </div>
                                @endforelse
                            </div>
                        </section>

                        <section class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
                                <h3 class="font-bold text-slate-800">Messages récents</h3>
                                <button onclick="switchTab('messages')"
                                    class="text-indigo-600 text-sm font-bold hover:underline">Voir tout</button>
                            </div>
                            <div class="divide-y divide-slate-50">
                                @forelse($messages->take(5) as $msg)
                                    <div class="p-6 flex items-center gap-4 hover:bg-slate-50 transition-all cursor-pointer">
                                        <div class="w-10 h-10 rounded-full overflow-hidden shadow-sm">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($msg->expediteur->name) }}&background=6366f1&color=fff"
                                                alt="">
                                        </div>
                                        <div class="flex-1 overflow-hidden">
                                            <div class="flex justify-between">
                                                <h4 class="font-bold text-slate-800 text-sm">{{ $msg->expediteur->name }}</h4>
                                                <span
                                                    class="text-[10px] text-slate-400 font-bold uppercase">{{ $msg->created_at->shortRelativeDiffForHumans() }}</span>
                                            </div>
                                            <p class="text-xs text-slate-500 truncate mt-0.5">{{ $msg->contenu }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-12 text-center text-slate-400">
                                        <p>Aucun message.</p>
                                    </div>
                                @endforelse
                            </div>
                        </section>
                    </div>
                </div>

                <div id="tab-jobs" class="tab-pane hidden">
                    <form method="GET" action="{{ route('candidat.dashboard') }}"
                        class="mb-8 flex flex-col md:flex-row gap-4 flex-wrap">
                        <input type="hidden" name="tab" value="jobs">
                        <div class="flex-1 relative min-w-[200px]">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Mot-clé..."
                                class="w-full pl-12 pr-4 py-4 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all shadow-sm">
                        </div>
                        <div class="flex-1 relative min-w-[150px]">
                            <i class="fas fa-map-marker-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="lieu" value="{{ request('lieu') }}" placeholder="Lieu..."
                                class="w-full pl-12 pr-4 py-4 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all shadow-sm">
                        </div>
                        <div class="flex-1 relative min-w-[150px]">
                            <i class="fas fa-wallet absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="number" name="salaire_min" value="{{ request('salaire_min') }}"
                                placeholder="Salaire min..."
                                class="w-full pl-12 pr-4 py-4 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all shadow-sm">
                        </div>
                        <div class="flex gap-2 w-full md:w-auto">
                            <select name="type_contrat"
                                class="flex-1 md:w-auto px-4 py-4 rounded-2xl border border-slate-200 bg-white shadow-sm outline-none font-bold text-slate-600 text-sm">
                                <option value="">Contrat</option>
                                <option value="cdi" {{ request('type_contrat') == 'cdi' ? 'selected' : '' }}>CDI</option>
                                <option value="cdd" {{ request('type_contrat') == 'cdd' ? 'selected' : '' }}>CDD</option>
                                <option value="stage" {{ request('type_contrat') == 'stage' ? 'selected' : '' }}>Stage
                                </option>
                                <option value="alternance" {{ request('type_contrat') == 'alternance' ? 'selected' : '' }}>
                                    Alternance</option>
                                <option value="freelance" {{ request('type_contrat') == 'freelance' ? 'selected' : '' }}>
                                    Freelance</option>
                            </select>
                            <select name="teletravail"
                                class="flex-1 md:w-auto px-4 py-4 rounded-2xl border border-slate-200 bg-white shadow-sm outline-none font-bold text-slate-600 text-sm">
                                <option value="">Télétravail</option>
                                <option value="non" {{ request('teletravail') == 'non' ? 'selected' : '' }}>Sur site</option>
                                <option value="partiel" {{ request('teletravail') == 'partiel' ? 'selected' : '' }}>Partiel
                                </option>
                                <option value="total" {{ request('teletravail') == 'total' ? 'selected' : '' }}>Total</option>
                            </select>
                            <button type="submit"
                                class="bg-indigo-600 text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                    </form>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="jobs-container">
                        @foreach($offres as $offre)
                            <div class="job-card bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-indigo-50 transition-all group border-b-4 border-b-transparent hover:border-b-indigo-500"
                                data-title="{{ strtolower($offre->titre) }}"
                                data-company="{{ strtolower($offre->recruteur->nom_entreprise) }}"
                                data-location="{{ strtolower($offre->lieu) }}">
                                <div class="flex justify-between items-start mb-6">
                                    <div
                                        class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-2xl group-hover:scale-110 transition-transform">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div class="flex flex-col gap-1 items-end">
                                        <div class="flex gap-2">
                                            <form action="{{ route('candidat.offres.favori', $offre->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="w-8 h-8 rounded-lg flex items-center justify-center transition-all {{ in_array($offre->id, $favorisIds) ? 'bg-rose-50 text-rose-500' : 'bg-slate-50 text-slate-400 hover:text-rose-500' }}">
                                                    <i
                                                        class="{{ in_array($offre->id, $favorisIds) ? 'fas' : 'far' }} fa-heart"></i>
                                                </button>
                                            </form>
                                            <span
                                                class="px-3 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-tighter">{{ $offre->type_contrat }}</span>
                                        </div>
                                        <span class="text-xs font-bold text-slate-400 italic">Il y a
                                            {{ $offre->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <h4 class="text-xl font-black text-slate-800 leading-tight mb-2">{{ $offre->titre }}</h4>
                                <p class="text-slate-500 font-bold mb-4 flex items-center gap-2">
                                    <span class="text-indigo-600">@</span>{{ $offre->recruteur->nom_entreprise }}
                                </p>

                                <div class="flex flex-wrap gap-2 mb-6">
                                    <div
                                        class="px-3 py-1.5 rounded-xl bg-slate-50 text-slate-600 text-xs font-bold flex items-center gap-1.5 border border-slate-100">
                                        <i class="fas fa-map-marker-alt text-indigo-400"></i> {{ $offre->lieu }}
                                    </div>
                                    @if($offre->salaire_min)
                                        <div
                                            class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-600 text-xs font-bold flex items-center gap-1.5 border border-emerald-100">
                                            <i class="fas fa-wallet"></i> {{ number_format($offre->salaire_min, 0, ',', ' ') }}€+
                                        </div>
                                    @endif
                                </div>

                                <div class="flex gap-3 mt-4">
                                    <button
                                        class="flex-1 px-5 py-3 rounded-2xl border-2 border-indigo-100 text-indigo-600 bg-white hover:bg-indigo-50 hover:border-indigo-200 text-sm font-black transition-all flex items-center justify-center"
                                        onclick="showOfferDetails({{ $offre->id }})">
                                        Détails
                                    </button>
                                        @if(in_array($offre->id, $appliedOfferIds))
                                            <button
                                                class="flex-[2] px-5 py-3 rounded-2xl bg-emerald-50 text-emerald-600 font-black cursor-default text-sm flex items-center justify-center gap-2"
                                                disabled>
                                                <i class="fas fa-check-circle"></i> Postulé
                                            </button>
                                        @else
                                            <button
                                                class="flex-[2] px-5 py-3 rounded-2xl bg-indigo-600 text-white font-black hover:bg-indigo-700 transition-all active:scale-95 shadow-lg shadow-indigo-200 text-sm flex items-center justify-center gap-2"
                                                onclick="showOfferDetails({{ $offre->id }})">
                                                Postuler <i class="fas fa-paper-plane text-[11px]"></i>
                                            </button>
                                        @endif
                                </div>
                                @if(in_array($offre->id, $appliedOfferIds))
                                    <div
                                        class="mt-4 flex items-center justify-center gap-2 text-emerald-600 bg-emerald-50/50 py-2.5 rounded-xl border border-emerald-100/50">
                                        <i class="fas fa-check-circle text-xs"></i>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Vous avez déjà postulé à
                                            cette offre</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div id="tab-applications" class="tab-pane hidden">
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Offre
                                        & Entreprise</th>
                                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Date
                                        d'envoi</th>
                                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Statut
                                    </th>
                                    <th
                                        class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-right">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($candidatures as $app)
                                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                                        <td class="px-8 py-6">
                                            <h5 class="font-bold text-slate-800 text-base">
                                                {{ $app->offre ? $app->offre->titre : 'Offre indisponible' }}
                                            </h5>
                                            <p class="text-sm text-indigo-500 font-bold mt-0.5">
                                                {{ $app->offre && $app->offre->recruteur ? $app->offre->recruteur->nom_entreprise : 'Entreprise inconnue' }}
                                            </p>
                                        </td>
                                        <td class="px-8 py-6">
                                            <p class="text-sm font-bold text-slate-500">{{ $app->created_at->format('d M Y') }}
                                            </p>
                                            <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">
                                                {{ $app->created_at->format('H:i') }}
                                            </p>
                                        </td>
                                        <td class="px-8 py-6">
                                            @php
                                                $badgeStyle = [
                                                    'en_attente' => 'bg-slate-100 text-slate-600',
                                                    'en_cours' => 'bg-indigo-100 text-indigo-600 border-indigo-200',
                                                    'acceptee' => 'bg-emerald-100 text-emerald-600 border-emerald-200',
                                                    'refusee' => 'bg-rose-100 text-rose-600 border-rose-200'
                                                ][$app->statut] ?? 'bg-slate-100 text-slate-600';
                                            @endphp
                                            <span
                                                class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $badgeStyle }}">
                                                {{ str_replace('_', ' ', $app->statut) }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-right flex items-center justify-end gap-2">
                                            <button
                                                onclick="openCandidatureChat({{ $app->id }}, '{{ addslashes($app->offre->recruteur->user->name) }}', {{ $app->offre->recruteur->user->id }})"
                                                class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm"
                                                title="Discuter avec le recruteur">
                                                <i class="fas fa-comments"></i>
                                            </button>
                                            <button class="text-slate-400 hover:text-slate-800 p-2.5 rounded-xl transition-all">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="tab-interviews" class="tab-pane hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse($entretiens as $int)
                            <div
                                class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $int->statut == 'planifie' ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600' }}">
                                        {{ $int->statut }}
                                    </span>
                                </div>
                                <div
                                    class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 mb-6 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-calendar-check text-2xl text-indigo-500"></i>
                                </div>
                                <h4 class="text-lg font-black text-slate-800 mb-2 leading-tight">
                                    {{ $int->candidature && $int->candidature->offre ? $int->candidature->offre->titre : 'Poste non défini' }}
                                </h4>
                                <p class="text-sm font-bold text-indigo-500 mb-6">
                                    {{ $int->candidature && $int->candidature->offre && $int->candidature->offre->recruteur ? $int->candidature->offre->recruteur->nom_entreprise : 'Entreprise inconnue' }}
                                </p>

                                <div class="space-y-3 mb-8">
                                    <div class="flex items-center gap-3 text-sm text-slate-600 font-medium">
                                        <i class="fas fa-calendar text-slate-400 w-4"></i>
                                        {{ $int->date_entretien->format('d F Y') }}
                                    </div>
                                    <div class="flex items-center gap-3 text-sm text-slate-600 font-medium">
                                        <i class="fas fa-clock text-slate-400 w-4"></i>
                                        {{ $int->date_entretien->format('H:i') }}
                                    </div>
                                    <div class="flex items-center gap-3 text-sm text-slate-600 font-medium">
                                        <i class="fas fa-video text-slate-400 w-4"></i>
                                        {{ ucfirst($int->type) }}
                                    </div>
                                </div>

                                @if($int->statut == 'planifie')
                                    <form action="{{ route('candidat.entretiens.confirmer', $int->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 text-white py-4 rounded-2xl font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100 mb-2">
                                            <i class="fas fa-check"></i> Confirmer ma présence
                                        </button>
                                    </form>
                                    <form action="{{ route('entretiens.annuler', $int->id) }}" method="POST"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cet entretien ?')">
                                        @csrf
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 bg-rose-50 text-rose-600 py-4 rounded-2xl font-bold hover:bg-rose-600 hover:text-white transition-all mb-4">
                                            <i class="fas fa-times"></i> Annuler l'entretien
                                        </button>
                                    </form>
                                @endif

                                @if($int->type == 'visio' && $int->lien_visio)
                                    <a href="{{ $int->lien_visio }}" target="_blank"
                                        class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 text-white py-4 rounded-2xl font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                                        <i class="fas fa-video"></i> Rejoindre la visio
                                    </a>
                                @else
                                    <div class="w-full text-center py-4 rounded-2xl bg-slate-50 text-slate-400 font-bold text-sm">
                                        {{ $int->statut == 'confirme' ? 'Entretien confirmé' : 'Attente de confirmation' }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="col-span-full py-20 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-calendar-day text-3xl text-slate-200"></i>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800">Aucun entretien</h3>
                                <p class="text-slate-500 mt-2">Vous n'avez pas encore d'entretiens planifiés.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div id="tab-favoris" class="tab-pane hidden">
                    <div class="mb-8">
                        <h3 class="text-2xl font-black text-slate-800">Mes Favoris</h3>
                        <p class="text-slate-500 font-bold uppercase tracking-widest text-xs mt-1">Retrouvez toutes les
                            offres que vous avez sauvegardées</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($offresFavorites as $offre)
                            <div
                                class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all group relative">
                                <div class="flex justify-between items-start mb-4">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <form action="{{ route('candidat.offres.favori', $offre->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </form>
                                </div>
                                <h4 class="text-lg font-black text-slate-800 leading-tight mb-1">{{ $offre->titre }}</h4>
                                <p class="text-indigo-500 font-bold text-sm mb-4">{{ $offre->recruteur->nom_entreprise }}</p>

                                <div class="flex items-center gap-4 text-xs text-slate-500 font-bold mb-6">
                                    <span><i class="fas fa-map-marker-alt mr-1"></i> {{ $offre->lieu }}</span>
                                    <span><i class="fas fa-clock mr-1"></i> {{ $offre->type_contrat }}</span>
                                </div>

                                <button onclick="showOfferDetails({{ $offre->id }})"
                                    class="w-full py-3 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:bg-black transition-all">
                                    Voir l'offre
                                </button>
                            </div>
                        @empty
                            <div class="col-span-full py-20 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="far fa-heart text-3xl text-slate-200"></i>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800">Aucun favori</h3>
                                <p class="text-slate-500 mt-2">Vous n'avez pas encore enregistré d'offres.</p>
                                <button onclick="switchTab('jobs')"
                                    class="mt-6 px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold text-sm">
                                    Parcourir les offres
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div id="tab-messages" class="tab-pane hidden">
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm flex h-[70vh] overflow-hidden">
                        <!-- Chat List -->
                        <div class="w-1/3 border-r border-slate-100 flex flex-col">
                            <div class="p-6 border-b border-slate-100">
                                <h3 class="font-black text-slate-800 text-lg">Conversations</h3>
                            </div>
                            <div class="flex-1 overflow-y-auto divide-y divide-slate-50" id="chat-list">
                                @forelse($conversations as $otherUserId => $msgs)
                                    @php 
                                        // Le dernier message de la conversation
                                        $lastMsg  = $msgs->last();
                                        // L'autre participant : si c'est moi l'expéditeur, l'autre est le destinataire
                                        $otherUser = ($lastMsg->expediteur_id == Auth::id())
                                            ? $lastMsg->destinataire
                                            : $lastMsg->expediteur;
                                        $unread = $msgs->where('destinataire_id', Auth::id())->whereNull('lu_at')->count();
                                    @endphp
                                    @if($otherUser)
                                    <div class="p-4 hover:bg-slate-50 transition-all cursor-pointer flex gap-3 items-center chat-item-{{ $otherUserId }}"
                                        onclick="openChat({{ $otherUserId }})">
                                        <div class="relative flex-shrink-0">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($otherUser->name) }}&background=6366f1&color=fff"
                                                class="w-11 h-11 rounded-full shadow-sm" alt="">
                                            @if($unread > 0)
                                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white text-[10px] font-black rounded-full flex items-center justify-center">{{ $unread }}</span>
                                            @endif
                                        </div>
                                        <div class="overflow-hidden flex-1">
                                            <h4 class="font-bold text-slate-800 text-sm truncate">{{ $otherUser->name }}</h4>
                                            <p class="text-xs text-slate-500 truncate mt-0.5 font-medium">
                                                @if($lastMsg->expediteur_id == Auth::id())<span class="text-indigo-400">Vous : </span>@endif
                                                {{ $lastMsg->contenu }}
                                            </p>
                                        </div>
                                        <span class="text-[10px] text-slate-400 flex-shrink-0">{{ $lastMsg->created_at->diffForHumans() }}</span>
                                    </div>
                                    @endif
                                @empty
                                    <div class="p-8 text-center text-slate-400">
                                        <i class="fas fa-comments text-3xl mb-3 block opacity-30"></i>
                                        <p class="text-xs font-bold uppercase tracking-widest">Aucune conversation</p>
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
                            <div id="chat-window" class="hidden flex-1 flex flex-col">
                                <div class="p-6 bg-white border-b border-slate-100 flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full overflow-hidden shadow-sm">
                                        <img id="chat-user-avatar" src="" alt="">
                                    </div>
                                    <h4 id="chat-user-name" class="font-bold text-slate-800"></h4>
                                </div>
                                <div id="chat-messages" class="flex-1 p-8 overflow-y-auto space-y-4">
                                    <!-- Messages will be loaded here -->
                                </div>
                                <div class="p-6 bg-white border-t border-slate-100">
                                    <form id="chat-form" action="{{ route('candidat.message.send') }}" method="POST"
                                        class="flex gap-4" data-ajax="true">
                                        @csrf
                                        <input type="hidden" name="destinataire_id" id="chat-dest-id">
                                        <input type="hidden" name="candidature_id" id="chat-candidature-id">
                                        <input type="text" name="contenu" placeholder="Écrivez votre message..."
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

                <div id="tab-profile" class="tab-pane hidden">
                    <div class="max-w-4xl mx-auto">
                        <form action="{{ route('candidat.profile.update') }}" method="POST" class="space-y-8">
                            @csrf
                            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                                <h3 class="font-black text-slate-800 text-xl mb-8 flex items-center gap-3">
                                    <i class="fas fa-id-card text-indigo-500"></i> Informations Professionnelles
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label
                                            class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Titre
                                            Professionnel</label>
                                        <input type="text" name="titre_professionnel"
                                            value="{{ $candidat->titre_professionnel }}"
                                            placeholder="Ex: Développeur Full Stack Senior"
                                            class="w-full px-5 py-4 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-bold text-slate-700">
                                    </div>
                                    <div class="space-y-2">
                                        <label
                                            class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Niveau
                                            d'expérience</label>
                                        <select name="niveau_experience"
                                            class="w-full px-5 py-4 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-bold text-slate-700">
                                            <option value="debutant" {{ $candidat->niveau_experience == 'debutant' ? 'selected' : '' }}>Débutant</option>
                                            <option value="junior" {{ $candidat->niveau_experience == 'junior' ? 'selected' : '' }}>Junior</option>
                                            <option value="confirme" {{ $candidat->niveau_experience == 'confirme' ? 'selected' : '' }}>Confirmé</option>
                                            <option value="senior" {{ $candidat->niveau_experience == 'senior' ? 'selected' : '' }}>Senior</option>
                                            <option value="expert" {{ $candidat->niveau_experience == 'expert' ? 'selected' : '' }}>Expert</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label
                                            class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Disponibilité</label>
                                        <select name="disponibilite"
                                            class="w-full px-5 py-4 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-bold text-slate-700">
                                            <option value="immediate" {{ $candidat->disponibilite == 'immediate' ? 'selected' : '' }}>Immédiate</option>
                                            <option value="1_mois" {{ $candidat->disponibilite == '1_mois' ? 'selected' : '' }}>1 mois</option>
                                            <option value="3_mois" {{ $candidat->disponibilite == '3_mois' ? 'selected' : '' }}>3 mois</option>
                                            <option value="6_mois" {{ $candidat->disponibilite == '6_mois' ? 'selected' : '' }}>6 mois</option>
                                            <option value="plus_6_mois" {{ $candidat->disponibilite == 'plus_6_mois' ? 'selected' : '' }}>Plus de 6 mois</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label
                                            class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Prétention
                                            Salariale (€/an)</label>
                                        <input type="number" name="pretention_salariale"
                                            value="{{ (int) $candidat->pretention_salariale }}" placeholder="Ex: 45000"
                                            class="w-full px-5 py-4 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-bold text-slate-700">
                                    </div>
                                    <div
                                        class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                        <input type="checkbox" name="is_open_to_relocation" id="relocation" value="1" {{ $candidat->is_open_to_relocation ? 'checked' : '' }}
                                            class="w-5 h-5 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                                        <label for="relocation" class="text-sm font-bold text-slate-700">Prêt à la mobilité
                                            géographique</label>
                                    </div>
                                    <div class="space-y-2">
                                        <label
                                            class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Compétences
                                            (séparées par des virgules)</label>
                                        <input type="text" name="competences"
                                            value="{{ is_array($candidat->competences) ? implode(', ', $candidat->competences) : '' }}"
                                            placeholder="PHP, Laravel, Vue.js, Design..."
                                            class="w-full px-5 py-4 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-bold text-slate-700">
                                    </div>
                                    <div class="space-y-2">
                                        <label
                                            class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Langues
                                            (séparées par des virgules)</label>
                                        <input type="text" name="langues"
                                            value="{{ is_array($candidat->langues) ? implode(', ', $candidat->langues) : '' }}"
                                            placeholder="Français, Anglais, Allemand..."
                                            class="w-full px-5 py-4 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-bold text-slate-700">
                                    </div>
                                    <div class="space-y-2">
                                        <label
                                            class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">LinkedIn
                                            (URL)</label>
                                        <input type="url" name="linkedin_url" value="{{ $candidat->linkedin_url }}"
                                            placeholder="https://linkedin.com/in/..."
                                            class="w-full px-5 py-4 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-bold text-slate-700">
                                    </div>
                                </div>
                                <button type="submit"
                                    class="mt-8 w-full py-5 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all">
                                    Enregistrer les modifications
                                </button>
                            </div>
                        </form>

                        <!-- Expériences Section -->
                        <div class="mt-8 bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                            <div class="flex justify-between items-center mb-8">
                                <h3 class="font-black text-slate-800 text-xl flex items-center gap-3">
                                    <i class="fas fa-briefcase text-indigo-500"></i> Expériences Professionnelles
                                </h3>
                                <button onclick="document.getElementById('modal-add-experience').classList.remove('hidden')"
                                    class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold text-xs hover:bg-indigo-600 hover:text-white transition-all">
                                    + Ajouter
                                </button>
                            </div>
                            <div class="space-y-6">
                                @forelse($candidat->experiences as $exp)
                                    <div class="flex items-start gap-4 p-6 rounded-2xl bg-slate-50 relative group">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-indigo-500">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-slate-800">{{ $exp->poste }}</h4>
                                            <p class="text-indigo-600 font-bold text-sm">{{ $exp->entreprise }} •
                                                {{ $exp->localisation }}
                                            </p>
                                            <p class="text-xs text-slate-400 font-bold mt-1 uppercase">
                                                {{ $exp->date_debut->format('M Y') }} -
                                                {{ $exp->en_cours ? 'Présent' : ($exp->date_fin ? $exp->date_fin->format('M Y') : 'N/A') }}
                                            </p>
                                            @if($exp->description)
                                                <p class="text-sm text-slate-500 mt-3 font-medium">{{ $exp->description }}</p>
                                            @endif
                                        </div>
                                        <form action="{{ route('candidat.experiences.delete', $exp->id) }}?tab=profile" method="POST"
                                            onsubmit="return confirm('Supprimer cette expérience ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-rose-400 hover:text-rose-600 transition-colors opacity-0 group-hover:opacity-100">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-center py-8 text-slate-400 font-bold italic text-sm">Aucune expérience
                                        enregistrée.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Formations Section -->
                        <div class="mt-8 bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                            <div class="flex justify-between items-center mb-8">
                                <h3 class="font-black text-slate-800 text-xl flex items-center gap-3">
                                    <i class="fas fa-graduation-cap text-indigo-500"></i> Formations et Diplômes
                                </h3>
                                <button onclick="document.getElementById('modal-formation').classList.remove('hidden')"
                                    class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold text-xs hover:bg-indigo-600 hover:text-white transition-all">
                                    + Ajouter
                                </button>
                            </div>
                            <div class="space-y-6">
                                @forelse($candidat->formations as $form)
                                    <div class="flex items-start gap-4 p-6 rounded-2xl bg-slate-50 relative group">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-indigo-500">
                                            <i class="fas fa-university"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-slate-800">{{ $form->diplome }}</h4>
                                            <p class="text-indigo-600 font-bold text-sm">{{ $form->etablissement }} •
                                                {{ $form->domaine }}
                                            </p>
                                            <p class="text-xs text-slate-400 font-bold mt-1 uppercase">
                                                Promotion {{ $form->date_debut }} - {{ $form->date_fin ?? '...' }}
                                            </p>
                                            @if($form->mention)
                                                <p class="text-xs font-black text-emerald-600 mt-1 uppercase tracking-widest">
                                                    Mention: {{ $form->mention }}</p>
                                            @endif
                                        </div>
                                        <form action="{{ route('candidat.formations.delete', $form->id) }}?tab=profile" method="POST"
                                            onsubmit="return confirm('Supprimer cette formation ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-rose-400 hover:text-rose-600 transition-colors opacity-0 group-hover:opacity-100">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-center py-8 text-slate-400 font-bold italic text-sm">Aucune formation
                                        enregistrée.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- Offer Details Modal -->
        <div id="offerModal"
            class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
            <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden animate-fadeIn">
                <div class="p-8 border-b border-slate-100 flex justify-between items-start">
                    <div class="flex gap-4 items-center">
                        <div
                            class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-2xl">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <h3 id="modal-title" class="text-2xl font-black text-slate-800"></h3>
                            <p id="modal-company" class="text-indigo-600 font-bold"></p>
                        </div>
                    </div>
                    <button onclick="closeOfferModal()"
                        class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:text-slate-800 transition-all flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="modal-postuler-form" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-8 max-h-[60vh] overflow-y-auto space-y-8">
                        <div>
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Description du
                                poste</h4>
                            <p id="modal-description"
                                class="text-slate-600 leading-relaxed font-medium whitespace-pre-line text-sm bg-slate-50 p-6 rounded-2xl border border-slate-100">
                            </p>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                            <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Type de
                                    contrat</p>
                                <p id="modal-contract" class="font-bold text-indigo-900 uppercase text-xs"></p>
                            </div>
                            <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Lieu</p>
                                <p id="modal-location" class="font-bold text-indigo-900 text-xs"></p>
                            </div>
                            <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                                <p class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-1">Télétravail</p>
                                <p id="modal-teletravail" class="font-bold text-indigo-900 text-xs"></p>
                            </div>
                            <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                                <p class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-1">Expérience</p>
                                <p id="modal-experience" class="font-bold text-indigo-900 text-xs"></p>
                            </div>
                            <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                                <p class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-1">Date limite</p>
                                <p id="modal-deadline" class="font-bold text-indigo-900 text-xs"></p>
                            </div>
                            <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                                <p class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-1">Postes</p>
                                <p id="modal-postes" class="font-bold text-indigo-900 text-xs"></p>
                            </div>
                            <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100">
                                <p class="text-xs font-black text-emerald-500 uppercase tracking-widest mb-1">Candidatures</p>
                                <p id="modal-candidatures-count" class="font-bold text-emerald-700 text-xs"></p>
                            </div>
                        </div>

                        <div id="modal-competences-section" class="mb-6 hidden">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Compétences requises</h4>
                            <div id="modal-competences" class="flex flex-wrap gap-2">
                            </div>
                        </div>

                        <hr class="border-slate-100">

                        <div id="modal-postuler-section" class="space-y-6">
                            <h4 class="font-black text-slate-800 text-lg flex items-center gap-2">
                                <i class="fas fa-file-signature text-indigo-500"></i> Ma Candidature
                            </h4>

                            <div class="space-y-2">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Lettre de
                                    motivation</label>
                                <textarea name="lettre_motivation" rows="4" required
                                    placeholder="Expliquez pourquoi vous êtes le meilleur candidat..."
                                    class="w-full px-5 py-4 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-medium text-sm text-slate-600 resize-none"></textarea>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">CV (PDF,
                                    DOC, DOCX - Max 2Mo)</label>
                                <div class="relative group">
                                    <input type="file" name="cv" required accept=".pdf,.doc,.docx"
                                        class="w-full px-5 py-4 rounded-2xl border border-slate-200 bg-white outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-bold text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Salaire
                                        souhaité (€/an)</label>
                                    <input type="number" name="pretention_salariale"
                                        value="{{ (int) $candidat->pretention_salariale }}"
                                        class="w-full px-5 py-4 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-bold text-slate-700 text-sm">
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Disponibilité</label>
                                    <select name="disponibilite"
                                        class="w-full px-5 py-4 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-bold text-slate-700 text-sm">
                                        <option value="immediate" {{ $candidat->disponibilite == 'immediate' ? 'selected' : '' }}>Immédiate</option>
                                        <option value="1_mois" {{ $candidat->disponibilite == '1_mois' ? 'selected' : '' }}>1
                                            mois</option>
                                        <option value="3_mois" {{ $candidat->disponibilite == '3_mois' ? 'selected' : '' }}>3
                                            mois</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="modal-already-applied-msg"
                            class="hidden p-8 text-center bg-emerald-50 rounded-2xl border border-emerald-100">
                            <div
                                class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <h4 class="text-xl font-black text-emerald-900 mb-2">Candidature déjà envoyée</h4>
                            <p class="text-emerald-600 font-medium text-sm">Vous avez déjà postulé à cette offre d'emploi.
                                Vous pouvez suivre l'état de votre candidature dans l'onglet "Suivi des candidatures".</p>
                        </div>
                    </div>
                    <div class="p-8 bg-slate-50 border-t border-slate-100 flex gap-4">
                        <button type="button" onclick="closeOfferModal()"
                            class="flex-1 px-8 py-5 rounded-2xl font-black text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all uppercase tracking-widest text-xs">
                            Annuler
                        </button>
                        <button id="modal-submit-btn" type="submit"
                            class="flex-[2] bg-indigo-600 text-white px-8 py-5 rounded-2xl font-black hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i> Envoyer ma candidature
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Candidature Message Modal for Candidate -->
        <div id="candidatureMessageModal"
            class="fixed inset-0 bg-black bg-opacity-50 hidden z-[70] flex items-center justify-center p-4 modal-backdrop">
            <div
                class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl transform transition-all duration-300">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-indigo-600 text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center font-bold"
                            id="cand_chat_avatar">?</div>
                        <div>
                            <h3 class="font-bold" id="cand_chat_name">Recruteur</h3>
                            <p class="text-[10px] uppercase tracking-widest opacity-80 font-bold">Discussion directe</p>
                        </div>
                    </div>
                    <button onclick="closeCandidatureMessageModal()"
                        class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="cand_chat_messages" class="h-[400px] p-6 overflow-y-auto space-y-4 bg-slate-50">
                    <!-- Messages populated via JS -->
                </div>
                <div class="p-6 bg-white border-t border-gray-100">
                    <form id="cand-modal-chat-form" action="{{ route('candidat.message.send') }}" onsubmit="handleCandChatSubmit(event)" class="flex gap-2" data-ajax="true">
                        @csrf
                        <input type="hidden" name="destinataire_id" id="cand_chat_dest_id">
                        <input type="hidden" name="candidature_id" id="cand_chat_candidature_id">
                        <input type="text" id="cand_chat_contenu" name="contenu" placeholder="Écrivez un message..."
                            class="flex-1 px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-medium">
                        <button type="submit"
                            class="w-12 h-12 bg-indigo-600 text-white rounded-xl flex items-center justify-center hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <style>
            .nav-link.active-nav {
                background-color: #f8fafc;
                color: #4f46e5;
                box-shadow: inset 4px 0 0 #4f46e5;
            }

            .tab-pane {
                animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
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

            .job-card {
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            }
        </style>

        @push('scripts')
            <script>
                const allOffres = @json($offres);
                const appliedOfferIds = @json($appliedOfferIds);

                function showOfferDetails(id) {
                    const offre = allOffres.find(o => o.id == id);
                    if (!offre) return;

                    document.getElementById('modal-title').textContent = offre.titre;
                    document.getElementById('modal-company').textContent = offre.recruteur.nom_entreprise;
                    document.getElementById('modal-description').textContent = offre.description;
                    document.getElementById('modal-contract').textContent = offre.type_contrat || 'Non spécifié';
                    document.getElementById('modal-location').textContent = offre.lieu || 'Non spécifié';
                    document.getElementById('modal-teletravail').textContent = offre.teletravail || 'Non spécifié';
                    document.getElementById('modal-experience').textContent = offre.experience_req || 'Non spécifiée';
                    
                    if (offre.date_limite) {
                        const date = new Date(offre.date_limite);
                        document.getElementById('modal-deadline').textContent = date.toLocaleDateString('fr-FR');
                    } else {
                        document.getElementById('modal-deadline').textContent = 'Non spécifiée';
                    }
                    
                    document.getElementById('modal-postes').textContent = offre.nb_postes || '1';
                    document.getElementById('modal-candidatures-count').textContent = (offre.candidatures_count || 0) + ' personne(s)';

                    const compSection = document.getElementById('modal-competences-section');
                    const compContainer = document.getElementById('modal-competences');
                    
                    let competences = offre.competences_req;
                    if (typeof competences === 'string') {
                        try {
                            competences = JSON.parse(competences);
                        } catch (e) {
                            competences = competences.split(',').map(c => c.trim());
                        }
                    }
                    
                    if (Array.isArray(competences) && competences.length > 0) {
                        compSection.classList.remove('hidden');
                        compContainer.innerHTML = competences.map(c => 
                            `<span class="px-3 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-bold">${c}</span>`
                        ).join('');
                    } else {
                        compSection.classList.add('hidden');
                    }

                    document.getElementById('modal-postuler-form').action = `/candidat/postuler/${id}`;

                    // Gérer l'affichage si déjà postulé
                    const postulerSection = document.getElementById('modal-postuler-section');
                    const submitBtn = document.getElementById('modal-submit-btn');
                    const appliedMsg = document.getElementById('modal-already-applied-msg');

                    if (appliedOfferIds.includes(id)) {
                        postulerSection.classList.add('hidden');
                        submitBtn.classList.add('hidden');
                        appliedMsg.classList.remove('hidden');
                    } else {
                        postulerSection.classList.remove('hidden');
                        submitBtn.classList.remove('hidden');
                        appliedMsg.classList.add('hidden');
                    }

                    document.getElementById('offerModal').classList.remove('hidden');
                }

                function closeOfferModal() {
                    document.getElementById('offerModal').classList.add('hidden');
                }

                function switchTab(tabId) {
                    // Close sidebar on mobile
                    const sidebar = document.querySelector('aside');
                    if (sidebar) sidebar.classList.remove('mobile-open');

                    // Update Nav
                    document.querySelectorAll('.nav-link').forEach(link => {
                        link.classList.remove('active-nav', 'text-indigo-600');
                        link.classList.add('text-slate-500');
                    });
                    const activeLink = document.getElementById('nav-' + tabId);
                    if (activeLink) {
                        activeLink.classList.add('active-nav');
                        activeLink.classList.remove('text-slate-500');
                    }

                    // Update Panes
                    document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));
                    const pane = document.getElementById('tab-' + tabId);
                    if (pane) pane.classList.remove('hidden');

                    // Update Header
                    const titles = {
                        'overview': "Vue d'ensemble",
                        'jobs': 'Rechercher des offres',
                        'applications': 'Suivi des candidatures',
                        'interviews': 'Mes entretiens',
                        'favoris': 'Mes Favoris',
                        'messages': 'Centre de messagerie',
                        'profile': 'Gestion du profil'
                    };
                    const titleEl = document.getElementById('current-tab-title');
                    if (titleEl) titleEl.textContent = titles[tabId] || tabId;
                }

                function toggleMobileSidebar() {
                    const sidebar = document.querySelector('aside');
                    if (sidebar) sidebar.classList.toggle('mobile-open');
                }

                // Handle tab parameter from URL and Premium interactions
                document.addEventListener('DOMContentLoaded', function () {
                    const urlParams = new URLSearchParams(window.location.search);
                    const tabParam = urlParams.get('tab');
                    if (tabParam) switchTab(tabParam);

                    // Dynamic experience date_fin handling
                    const expCurrentCheckbox = document.getElementById('exp-current');
                    const expDateFinInput = document.getElementById('exp-date-fin');
                    if (expCurrentCheckbox && expDateFinInput) {
                        const toggleDateFin = () => {
                            if (expCurrentCheckbox.checked) {
                                expDateFinInput.disabled = true;
                                expDateFinInput.value = '';
                                expDateFinInput.classList.add('opacity-50', 'bg-slate-50');
                            } else {
                                expDateFinInput.disabled = false;
                                expDateFinInput.classList.remove('opacity-50', 'bg-slate-50');
                            }
                        };
                        expCurrentCheckbox.addEventListener('change', toggleDateFin);
                        toggleDateFin();
                    }
                });

                // ==============================
                // Chat Logic
                // ==============================
                const allMessages = @json($messages);
                const authId = {{ Auth::id() }};

                function getCsrfToken() {
                    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                }

                function renderMessages(container, msgs) {
                    container.innerHTML = msgs
                        .sort((a, b) => new Date(a.created_at) - new Date(b.created_at))
                        .map(m => `
                            <div class="flex ${m.expediteur_id == authId ? 'justify-end' : 'justify-start'} mb-2">
                                <div class="max-w-[70%] px-5 py-3 rounded-2xl text-sm font-medium shadow-sm
                                    ${m.expediteur_id == authId
                                        ? 'bg-indigo-600 text-white rounded-br-none'
                                        : 'bg-white text-slate-700 border border-slate-100 rounded-bl-none'}">
                                    ${m.contenu}
                                </div>
                            </div>`)
                        .join('');
                    container.scrollTop = container.scrollHeight;
                }

                function openChat(userId) {
                    const chatWindow   = document.getElementById('chat-window');
                    const noChat       = document.getElementById('no-chat-selected');
                    const msgContainer = document.getElementById('chat-messages');
                    const destInput    = document.getElementById('chat-dest-id');
                    const candInput    = document.getElementById('chat-candidature-id');
                    if (!chatWindow || !msgContainer || !destInput) return;

                    chatWindow.classList.remove('hidden');
                    if (noChat) noChat.classList.add('hidden');

                    // Identify the other user
                    const userMsgs = allMessages.filter(m =>
                        m.expediteur_id == userId || m.destinataire_id == userId
                    );

                    // Try to find the most recent candidature_id from these messages
                    const lastMsgWithCand = [...userMsgs].reverse().find(m => m.candidature_id);
                    if (candInput) {
                        candInput.value = lastMsgWithCand ? lastMsgWithCand.candidature_id : '';
                    }
                    if (userMsgs.length > 0) {
                        const sample    = userMsgs[0];
                        const otherUser = sample.expediteur_id == userId ? sample.expediteur : sample.destinataire;
                        if (otherUser) {
                            const nameEl   = document.getElementById('chat-user-name');
                            const avatarEl = document.getElementById('chat-user-avatar');
                            if (nameEl)   nameEl.textContent = otherUser.name;
                            if (avatarEl) avatarEl.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(otherUser.name)}&background=6366f1&color=fff`;
                        }
                    }

                    destInput.value = userId;
                    renderMessages(msgContainer, userMsgs);

                    // Marquer comme lu
                    const unreadList = userMsgs.filter(m => m.destinataire_id == {{ Auth::id() }} && !m.lu_at);
                    if (unreadList.length > 0) {
                        fetch(`/messages/${userId}/read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        }).then(res => res.json()).then(data => {
                            if (data.success) {
                                // Mettre à jour localement
                                userMsgs.forEach(m => {
                                    if (m.destinataire_id == {{ Auth::id() }} && !m.lu_at) {
                                        m.lu_at = new Date().toISOString();
                                    }
                                });
                                // Masquer le badge dans la liste
                                const badge = document.querySelector(`.chat-item-${userId} .bg-rose-500`);
                                if (badge) badge.style.display = 'none';

                                // Mettre à jour les compteurs globaux
                                const statCard = document.getElementById('stat-unread-messages');
                                if (statCard) {
                                    let currentCount = parseInt(statCard.innerText) || 0;
                                    let newCount = Math.max(0, currentCount - unreadList.length);
                                    statCard.innerText = newCount;
                                    if (newCount === 0) {
                                        const tabBadge = document.getElementById('tab-badge-messages');
                                        if (tabBadge) tabBadge.style.display = 'none';
                                    }
                                }
                            }
                        }).catch(err => console.error('Erreur lecture', err));
                    }
                }

                function openCandidatureChat(candidatureId, recruiterName, recruiterId) {
                    const destEl   = document.getElementById('cand_chat_dest_id');
                    const candEl   = document.getElementById('cand_chat_candidature_id');
                    const nameEl   = document.getElementById('cand_chat_name');
                    const avatarEl = document.getElementById('cand_chat_avatar');
                    if (destEl)   destEl.value            = recruiterId;
                    if (candEl)   candEl.value            = candidatureId;
                    if (nameEl)   nameEl.textContent      = recruiterName;
                    if (avatarEl) avatarEl.textContent    = recruiterName.charAt(0).toUpperCase();

                    renderCandChat(recruiterId);

                    const modal = document.getElementById('candidatureMessageModal');
                    if (modal) modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }

                function closeCandidatureMessageModal() {
                    const modal = document.getElementById('candidatureMessageModal');
                    if (modal) modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }

                function renderCandChat(recruiterId) {
                    const container = document.getElementById('cand_chat_messages');
                    if (!container) return;
                    const msgs = allMessages.filter(m =>
                        m.expediteur_id == recruiterId || m.destinataire_id == recruiterId
                    );
                    if (msgs.length === 0) {
                        container.innerHTML = '<div class="text-center py-12 text-slate-400 font-bold text-xs uppercase tracking-widest">Aucun message — commencez la discussion !</div>';
                    } else {
                        renderMessages(container, msgs);
                        // Marquer comme lu
                        const unreadList = msgs.filter(m => m.destinataire_id == {{ Auth::id() }} && !m.lu_at);
                        if (unreadList.length > 0) {
                            fetch(`/messages/${recruiterId}/read`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': getCsrfToken(),
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
                                    // Mettre à jour les compteurs globaux
                                    const statCard = document.getElementById('stat-unread-messages');
                                    if (statCard) {
                                        let currentCount = parseInt(statCard.innerText) || 0;
                                        let newCount = Math.max(0, currentCount - unreadList.length);
                                        statCard.innerText = newCount;
                                        if (newCount === 0) {
                                            const tabBadge = document.getElementById('tab-badge-messages');
                                            if (tabBadge) tabBadge.style.display = 'none';
                                        }
                                    }
                                }
                            }).catch(err => console.error('Erreur lecture', err));
                        }
                    }
                }

                function sendMessage(formEl, onSuccess) {
                    // Read values directly — avoids FormData timing issues
                    const destInput    = formEl.querySelector('[name="destinataire_id"]');
                    const contenuInput = formEl.querySelector('[name="contenu"]');
                    const candInput    = formEl.querySelector('[name="candidature_id"]');

                    const destinataireId = destInput    ? destInput.value.trim()    : '';
                    const contenu        = contenuInput ? contenuInput.value.trim() : '';
                    const candidatureId  = candInput    ? candInput.value.trim()    : '';

                    if (!destinataireId) {
                        alert('Veuillez sélectionner une conversation avant d\'envoyer un message.');
                        return;
                    }
                    if (!contenu) {
                        alert('Veuillez écrire un message.');
                        contenuInput && contenuInput.focus();
                        return;
                    }

                    const submitBtn = formEl.querySelector('button[type="submit"]');
                    const origHtml  = submitBtn ? submitBtn.innerHTML : '';
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    }

                    const payload = {
                        _token:          getCsrfToken(),
                        destinataire_id: destinataireId,
                        contenu:         contenu,
                    };
                    if (candidatureId) payload.candidature_id = candidatureId;

                    fetch(formEl.action, {
                        method:  'POST',
                        headers: {
                            'Content-Type':     'application/json',
                            'Accept':           'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN':     getCsrfToken()
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(async res => {
                        const data = await res.json();
                        if (!res.ok) {
                            const msg = data.errors
                                ? Object.values(data.errors).flat().join(', ')
                                : (data.message || 'Erreur serveur ' + res.status);
                            throw new Error(msg);
                        }
                        return data;
                    })
                    .then(data => {
                        if (data.success && data.message) {
                            allMessages.push(data.message);
                            if (contenuInput) contenuInput.value = '';
                            onSuccess(data.message);
                        }
                    })
                    .catch(err => {
                        console.error('Message error:', err);
                        alert('Erreur lors de l\'envoi : ' + err.message);
                    })
                    .finally(() => {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = origHtml;
                        }
                    });
                }

                function handleCandChatSubmit(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const form = e.target;
                    sendMessage(form, msg => {
                        if (form.id === 'cand-modal-chat-form') {
                            renderCandChat(msg.destinataire_id);
                        } else {
                            openChat(msg.destinataire_id);
                        }
                    });
                }

                // Attach to main chat form
                document.addEventListener('DOMContentLoaded', function () {
                    const chatForm = document.getElementById('chat-form');
                    if (chatForm) chatForm.addEventListener('submit', handleCandChatSubmit);
                });
            </script>
        @endpush

        <!-- Experience Modal -->
        <div id="modal-add-experience"
            class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white w-full max-w-xl rounded-3xl shadow-2xl overflow-hidden animate-fadeIn">
                <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-indigo-600 text-white">
                    <h3 class="font-black text-xl">Ajouter une expérience</h3>
                    <button onclick="document.getElementById('modal-add-experience').classList.add('hidden')"
                        class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('candidat.experiences.add') }}?tab=profile" method="POST" class="p-8 space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Poste</label>
                            <input type="text" name="poste" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:border-indigo-400 font-bold text-sm">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Entreprise</label>
                            <input type="text" name="entreprise" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:border-indigo-400 font-bold text-sm">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Localisation</label>
                        <input type="text" name="localisation"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:border-indigo-400 font-bold text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Date
                                début</label>
                            <input type="date" name="date_debut" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:border-indigo-400 font-bold text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Date fin</label>
                            <input type="date" name="date_fin" id="exp-date-fin"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:border-indigo-400 font-bold text-sm">
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="en_cours" value="1" id="exp-current"
                            class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                        <label for="exp-current" class="text-xs font-bold text-slate-600 uppercase tracking-widest">Poste
                            actuel</label>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:border-indigo-400 font-medium text-sm resize-none"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-indigo-700 transition-all">Enregistrer</button>
                </form>
            </div>
        </div>

        <!-- Formation Modal -->
        <div id="modal-formation"
            class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white w-full max-w-xl rounded-3xl shadow-2xl overflow-hidden animate-fadeIn">
                <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-indigo-600 text-white">
                    <h3 class="font-black text-xl">Ajouter une formation</h3>
                    <button onclick="document.getElementById('modal-formation').classList.add('hidden')"
                        class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('candidat.formations.add') }}?tab=profile" method="POST" class="p-8 space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Diplôme</label>
                            <input type="text" name="diplome" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:border-indigo-400 font-bold text-sm">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Établissement</label>
                            <input type="text" name="etablissement" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:border-indigo-400 font-bold text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Domaine</label>
                            <input type="text" name="domaine"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:border-indigo-400 font-bold text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Niveau</label>
                            <select name="niveau"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:border-indigo-400 font-bold text-sm">
                                <option value="licence">Licence / Bac+3</option>
                                <option value="master">Master / Bac+5</option>
                                <option value="doctorat">Doctorat / Bac+8</option>
                                <option value="autre">Autre / Certification</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Année
                                début</label>
                            <input type="number" name="date_debut" required min="1950" max="2030"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:border-indigo-400 font-bold text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Année fin (ou
                                prévue)</label>
                            <input type="number" name="date_fin" min="1950" max="2035"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:border-indigo-400 font-bold text-sm">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Mention</label>
                        <input type="text" name="mention"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:border-indigo-400 font-bold text-sm">
                    </div>
                    <button type="submit"
                        class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-indigo-700 transition-all">Enregistrer</button>
                </form>
            </div>
        </div>
@endsection