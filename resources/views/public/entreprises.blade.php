@extends('layouts.app')

@section('title', 'Entreprises - RecruitPro')

@section('content')
    <div class="min-h-screen bg-slate-50 pb-20">
        <!-- Hero Section -->
        <div class="relative bg-indigo-900 overflow-hidden mb-12">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-indigo-800 to-slate-900 opacity-90"></div>
            
            <div class="relative container mx-auto px-6 py-20 lg:py-24 text-center z-10">
                <span class="inline-block py-1.5 px-4 rounded-full bg-white/10 text-indigo-100 text-xs font-bold tracking-widest uppercase mb-6 border border-white/20 backdrop-blur-sm shadow-sm">
                    Réseau d'excellence
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 tracking-tight">
                    Découvrez nos <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-cyan-300">Entreprises</span>
                </h1>
                <p class="text-indigo-100 text-lg md:text-xl max-w-2xl mx-auto font-medium mb-12 opacity-90">
                    Explorez les entreprises qui recrutent au Burkina Faso et trouvez l'environnement de travail qui vous correspond.
                </p>

                <!-- Search Bar Integrated -->
                <div class="max-w-3xl mx-auto bg-white/10 backdrop-blur-md p-2.5 rounded-[2rem] border border-white/20 shadow-2xl">
                    <form action="{{ route('public.entreprises') }}" method="GET" class="flex flex-col md:flex-row gap-2 bg-white rounded-3xl p-2">
                        <div class="flex-1 relative flex items-center">
                            <i class="fas fa-search absolute left-5 text-slate-400 text-lg"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Rechercher une entreprise, un secteur d'activité..."
                                class="w-full pl-12 pr-4 py-3.5 bg-transparent focus:outline-none text-slate-700 font-bold placeholder-slate-400">
                        </div>
                        <button type="submit"
                            class="bg-indigo-600 text-white px-8 py-3.5 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 w-full md:w-auto">
                            Rechercher
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Decorative Elements -->
            <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        </div>

        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-black text-slate-800">Nos <span class="text-indigo-600">Partenaires</span></h2>
                <span class="text-sm font-bold text-slate-500 bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">{{ $entreprises->total() }} résultats</span>
            </div>

            <!-- Companies Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($entreprises as $entreprise)
                    <div
                        class="bg-white rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 border border-slate-100 hover:border-indigo-100 transition-all duration-300 group flex flex-col items-center text-center relative overflow-hidden">
                        
                        <!-- Top decorative border on hover -->
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 to-cyan-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <!-- Logo / Avatar -->
                        <div
                            class="w-20 h-20 rounded-[1.2rem] bg-white shadow-sm border border-slate-100 flex items-center justify-center text-indigo-600 font-black text-3xl mb-4 group-hover:scale-110 group-hover:shadow-md group-hover:border-indigo-100 transition-all overflow-hidden relative">
                            <div class="absolute inset-0 bg-indigo-50/50"></div>
                            <div class="relative z-10 w-full h-full flex items-center justify-center p-2">
                                @if($entreprise->logo_entreprise)
                                    <img src="{{ asset('storage/' . $entreprise->logo_entreprise) }}" 
                                         alt="{{ $entreprise->nom_entreprise }}"
                                         class="max-w-full max-h-full object-contain"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <span style="display:none;">{{ substr($entreprise->nom_entreprise, 0, 1) }}</span>
                                @else
                                    <span>{{ substr($entreprise->nom_entreprise, 0, 1) }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Informations -->
                        <h3
                            class="text-lg font-black text-slate-800 mb-1 group-hover:text-indigo-600 transition-colors line-clamp-1 w-full">
                            {{ $entreprise->nom_entreprise }}
                        </h3>

                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 line-clamp-1 w-full flex items-center justify-center gap-1.5">
                            <i class="fas fa-tag"></i> {{ $entreprise->secteur_activite ?? 'Secteur non renseigné' }}
                        </p>

                        <div class="relative w-full flex-grow flex items-center justify-center">
                            <p class="text-sm text-slate-500 mb-6 line-clamp-3 leading-relaxed">
                                {{ $entreprise->description_ent ?? 'Cette entreprise n\'a pas encore ajouté de description.' }}
                            </p>
                        </div>

                        <!-- Footer Carte -->
                        <div class="w-full mt-auto space-y-3 pt-5 border-t border-slate-100">
                            <div
                                class="flex items-center justify-center gap-2 text-[11px] font-black uppercase tracking-widest {{ $entreprise->offres_count > 0 ? 'text-indigo-700 bg-indigo-50/80 border border-indigo-100' : 'text-slate-500 bg-slate-50 border border-slate-100' }} py-2 px-4 rounded-xl transition-colors">
                                <i class="fas fa-briefcase"></i>
                                {{ $entreprise->offres_count }} offre{{ $entreprise->offres_count > 1 ? 's' : '' }}
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('public.offres') }}?search={{ urlencode($entreprise->nom_entreprise) }}"
                                    class="flex-1 block text-center py-2.5 rounded-xl border border-slate-200 text-slate-600 font-black text-sm hover:border-indigo-300 hover:text-indigo-700 hover:bg-indigo-50 hover:-translate-y-0.5 transition-all shadow-sm">
                                    Voir Offres
                                </a>

                                @if($entreprise->site_web)
                                    <a href="{{ str_starts_with($entreprise->site_web, 'http') ? $entreprise->site_web : 'https://' . $entreprise->site_web }}"
                                        target="_blank" rel="noopener noreferrer"
                                        class="flex items-center justify-center w-12 rounded-xl border border-slate-200 text-slate-400 hover:border-indigo-300 hover:text-indigo-700 hover:bg-indigo-50 hover:-translate-y-0.5 transition-all shadow-sm"
                                        title="Visiter le site web">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-100">
                        <div
                            class="w-20 h-20 mx-auto rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mb-4">
                            <i class="fas fa-building text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Aucune entreprise trouvée</h3>
                        <p class="text-sm text-slate-500">Essayez de modifier vos critères de recherche.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-10 flex justify-center">
                {{ $entreprises->links() }}
            </div>
        </div>
    </div>
@endsection
