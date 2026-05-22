@extends('layouts.app')

@section('title', 'Offres d\'emploi - RecruitPro')

@section('content')
    <div class="min-h-screen bg-slate-50 pb-20">
        <!-- Hero Section -->
        <div class="relative bg-indigo-900 overflow-hidden mb-12">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-indigo-800 to-slate-900 opacity-90"></div>
            
            <div class="relative container mx-auto px-6 py-20 lg:py-24 text-center z-10">
                <span class="inline-block py-1.5 px-4 rounded-full bg-white/10 text-indigo-100 text-xs font-bold tracking-widest uppercase mb-6 border border-white/20 backdrop-blur-sm shadow-sm">
                    Rejoignez les meilleurs
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 tracking-tight">
                    Propulsez votre <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-cyan-300">Carrière</span>
                </h1>
                <p class="text-indigo-100 text-lg md:text-xl max-w-2xl mx-auto font-medium mb-12 opacity-90">
                    Découvrez des centaines d'opportunités professionnelles et trouvez le poste qui correspond parfaitement à vos ambitions.
                </p>

                <!-- Search Bar Integrated -->
                <div class="max-w-4xl mx-auto bg-white/10 backdrop-blur-md p-2.5 rounded-[2rem] border border-white/20 shadow-2xl">
                    <form action="{{ route('public.offres') }}" method="GET" class="flex flex-col md:flex-row gap-2 bg-white rounded-3xl p-2">
                        <div class="flex-1 relative flex items-center">
                            <i class="fas fa-search absolute left-5 text-slate-400 text-lg"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Poste, compétence, mot-clé..."
                                class="w-full pl-12 pr-4 py-3.5 bg-transparent focus:outline-none text-slate-700 font-bold placeholder-slate-400">
                        </div>
                        <div class="w-full md:w-56 border-t md:border-t-0 md:border-l border-slate-100">
                            <select name="type_contrat"
                                class="w-full px-5 py-3.5 bg-transparent focus:outline-none text-slate-600 font-bold cursor-pointer">
                                <option value="">Contrat (Tous)</option>
                                <option value="CDI" {{ request('type_contrat') == 'CDI' ? 'selected' : '' }}>CDI</option>
                                <option value="CDD" {{ request('type_contrat') == 'CDD' ? 'selected' : '' }}>CDD</option>
                                <option value="Stage" {{ request('type_contrat') == 'Stage' ? 'selected' : '' }}>Stage</option>
                                <option value="Alternance" {{ request('type_contrat') == 'Alternance' ? 'selected' : '' }}>Alternance</option>
                                <option value="Freelance" {{ request('type_contrat') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                            </select>
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
                <h2 class="text-2xl font-black text-slate-800">Dernières <span class="text-indigo-600">Offres</span></h2>
                <span class="text-sm font-bold text-slate-500 bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">{{ $offres->total() }} résultats</span>
            </div>

            <!-- Offers Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($offres as $offre)
                    <div
                        class="bg-white rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 border border-slate-100 hover:border-indigo-100 transition-all duration-300 group flex flex-col h-full relative overflow-hidden">
                        
                        <!-- Top decorative border on hover -->
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 to-cyan-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <!-- En-tête Carte -->
                        <div class="flex justify-between items-start mb-6">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-white shadow-sm border border-slate-100 text-indigo-600 flex items-center justify-center font-black text-2xl group-hover:scale-110 group-hover:shadow-md group-hover:border-indigo-100 transition-all overflow-hidden relative">
                                    <div class="absolute inset-0 bg-indigo-50/50"></div>
                                    <div class="relative z-10 w-full h-full flex items-center justify-center p-2">
                                        @if($offre->recruteur && $offre->recruteur->logo_entreprise)
                                            <img src="{{ asset('storage/' . $offre->recruteur->logo_entreprise) }}" 
                                                 class="max-w-full max-h-full object-contain"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <span style="display:none;" class="text-xl">{{ substr($offre->recruteur->nom_entreprise ?? 'E', 0, 1) }}</span>
                                        @else
                                            <span class="text-xl">{{ substr($offre->recruteur->nom_entreprise ?? 'E', 0, 1) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h3
                                        class="font-black text-slate-800 text-lg leading-tight group-hover:text-indigo-600 transition-colors line-clamp-1">
                                        {{ $offre->titre }}
                                    </h3>
                                    <p class="text-sm font-bold text-slate-500 mt-1 flex items-center gap-1.5">
                                        <i class="far fa-building text-slate-400"></i>
                                        {{ $offre->recruteur->nom_entreprise ?? 'Entreprise Confidentielle' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Tags -->
                        <div class="flex flex-wrap gap-2 mb-6">
                            <span
                                class="px-3.5 py-1.5 rounded-xl bg-indigo-50/80 border border-indigo-100 text-indigo-700 text-[11px] font-black uppercase tracking-widest flex items-center gap-1.5 transition-colors group-hover:bg-indigo-100/80">
                                <i class="fas fa-file-contract"></i> {{ $offre->type_contrat }}
                            </span>
                            <span
                                class="px-3.5 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-[11px] font-black uppercase tracking-widest flex items-center gap-1.5 transition-colors group-hover:bg-slate-100">
                                <i class="fas fa-map-marker-alt"></i> {{ $offre->lieu }}
                            </span>
                            @if($offre->teletravail != 'non')
                                <span
                                    class="px-3.5 py-1.5 rounded-xl bg-teal-50/80 border border-teal-100 text-teal-700 text-[11px] font-black uppercase tracking-widest flex items-center gap-1.5 transition-colors group-hover:bg-teal-100/80">
                                    <i class="fas fa-laptop-house"></i> Télétravail
                                </span>
                            @endif
                        </div>

                        <!-- Description -->
                        <p class="text-base text-slate-500 mb-6 line-clamp-3 leading-relaxed flex-grow">
                            {{ Str::limit(strip_tags($offre->description), 150) }}
                        </p>

                        <!-- Footer Carte -->
                        <div class="pt-5 border-t border-slate-100 mt-auto flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex items-center gap-2 text-sm font-bold text-slate-400">
                                <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-indigo-50 group-hover:text-indigo-400 transition-colors">
                                    <i class="far fa-clock"></i>
                                </div>
                                {{ $offre->created_at->diffForHumans() }}
                            </div>

                            <div class="flex gap-3 w-full sm:w-auto">
                                <button onclick="showOfferDetails({{ $offre->id }})"
                                    class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl border-2 border-indigo-100 text-indigo-600 bg-white hover:bg-indigo-50 hover:border-indigo-200 text-sm font-black transition-all text-center flex items-center justify-center">
                                    Détails
                                </button>
                                @auth
                                    @if(Auth::user()->isCandidat())
                                        @if(in_array($offre->id, $appliedOfferIds))
                                            <button disabled
                                                class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl bg-emerald-50 text-emerald-600 text-sm font-black border border-emerald-100 cursor-not-allowed text-center flex items-center justify-center gap-2">
                                                <i class="fas fa-check"></i> Postulée
                                            </button>
                                        @else
                                            <button onclick="showOfferDetails({{ $offre->id }})"
                                                class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-black shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all text-center flex items-center justify-center gap-2">
                                                Postuler <i class="fas fa-paper-plane text-[11px]"></i>
                                            </button>
                                        @endif
                                    @elseif(Auth::user()->isRecruteur())
                                        <span class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl bg-slate-50 text-xs font-black text-slate-400 uppercase tracking-widest text-center flex items-center justify-center border border-slate-100">
                                            Compte recruteur
                                        </span>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                        class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-black shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all text-center flex items-center justify-center gap-2">
                                        Postuler <i class="fas fa-paper-plane text-[11px]"></i>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-100">
                        <div
                            class="w-20 h-20 mx-auto rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mb-4">
                            <i class="fas fa-search text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Aucune offre trouvée</h3>
                        <p class="text-base text-slate-500">Essayez de modifier vos critères de recherche.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-10 flex justify-center">
                {{ $offres->links() }}
            </div>
        </div>
    </div>

    <!-- Offer Details Modal -->
    <div id="offerModal"
        class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden">
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
                        <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-3">Description du poste
                        </h4>
                        <p id="modal-description"
                            class="text-slate-600 leading-relaxed font-medium whitespace-pre-line text-base bg-slate-50 p-6 rounded-2xl border border-slate-100">
                        </p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                        <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                            <p class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-1">Type de contrat
                            </p>
                            <p id="modal-contract" class="font-bold text-indigo-900 uppercase text-sm"></p>
                        </div>
                        <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                            <p class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-1">Lieu</p>
                            <p id="modal-location" class="font-bold text-indigo-900 text-sm"></p>
                        </div>
                        <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                            <p class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-1">Télétravail</p>
                            <p id="modal-teletravail" class="font-bold text-indigo-900 text-sm"></p>
                        </div>
                        <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                            <p class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-1">Expérience</p>
                            <p id="modal-experience" class="font-bold text-indigo-900 text-sm"></p>
                        </div>
                        <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                            <p class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-1">Date limite</p>
                            <p id="modal-deadline" class="font-bold text-indigo-900 text-sm"></p>
                        </div>
                        <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                            <p class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-1">Postes</p>
                            <p id="modal-postes" class="font-bold text-indigo-900 text-sm"></p>
                        </div>
                        <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100">
                            <p class="text-xs font-black text-emerald-500 uppercase tracking-widest mb-1">Candidatures</p>
                            <p id="modal-candidatures-count" class="font-bold text-emerald-700 text-sm"></p>
                        </div>
                    </div>

                    <div id="modal-competences-section" class="mb-6 hidden">
                        <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-3">Compétences requises</h4>
                        <div id="modal-competences" class="flex flex-wrap gap-2">
                        </div>
                    </div>

                    @auth
                            @if(Auth::user()->isCandidat())
                                    <hr class="border-slate-100">

                                    <div id="modal-postuler-section" class="space-y-6">
                                        <h4 class="font-black text-slate-800 text-lg flex items-center gap-2">
                                            <i class="fas fa-file-signature text-indigo-500"></i> Ma Candidature
                                        </h4>

                                        <div class="space-y-2">
                                            <label class="text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Lettre de
                                                motivation</label>
                                            <textarea name="lettre_motivation" rows="4" required
                                                placeholder="Expliquez pourquoi vous êtes le meilleur candidat..."
                                                class="w-full px-5 py-4 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-medium text-base text-slate-600 resize-none"></textarea>
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-sm font-black text-slate-400 uppercase tracking-widest ml-1">CV (PDF, DOC,
                                                DOCX - Max 2Mo)</label>
                                            <input type="file" name="cv" required accept=".pdf,.doc,.docx"
                                                class="w-full px-5 py-4 rounded-2xl border border-slate-200 bg-white outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-bold text-base text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="space-y-2">
                                                <label class="text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Salaire
                                                    souhaité (€/an)</label>
                                                <input type="number" name="pretention_salariale"
                                                    value="{{ (int) (Auth::user()->candidat->pretention_salariale ?? 0) }}"
                                                    class="w-full px-5 py-4 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-bold text-slate-700 text-base">
                                            </div>
                                            <div class="space-y-2">
                                                <label
                                                    class="text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Disponibilité</label>
                                                <select name="disponibilite"
                                                    class="w-full px-5 py-4 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all font-bold text-slate-700 text-base">
                                                    <option value="immediate" {{ (Auth::user()->candidat->disponibilite ?? '') == 'immediate' ? 'selected' : '' }}>Immédiate</option>
                                                    <option value="1_mois" {{ (Auth::user()->candidat->disponibilite ?? '') == '1_mois' ? 'selected' : '' }}>1 mois</option>
                                                    <option value="3_mois" {{ (Auth::user()->candidat->disponibilite ?? '') == '3_mois' ? 'selected' : '' }}>3 mois</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="modal-already-applied-msg"
                                        class="hidden p-8 text-center bg-emerald-50 rounded-2xl border border-emerald-100 mt-6">
                                        <div
                                            class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                                            <i class="fas fa-check-double"></i>
                                        </div>
                                        <h4 class="text-xl font-black text-emerald-900 mb-2">Candidature déjà envoyée</h4>
                                        <p class="text-emerald-600 font-medium text-base">Vous avez déjà postulé à cette offre d'emploi. Vous
                                            pouvez suivre l'état de votre candidature depuis votre tableau de bord.</p>
                                    </div>

                                </div>
                                <div id="modal-submit-footer" class="p-8 bg-slate-50 border-t border-slate-100 flex gap-4">
                                    <button type="button" onclick="closeOfferModal()"
                                        class="flex-1 px-8 py-5 rounded-2xl font-black text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all uppercase tracking-widest text-sm">
                                        Annuler
                                    </button>
                                    <button type="submit"
                                        class="flex-[2] bg-indigo-600 text-white px-8 py-5 rounded-2xl font-black hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                                        <i class="fas fa-paper-plane"></i> Envoyer ma candidature
                                    </button>
                                </div>

                                <div id="modal-close-only-footer" class="hidden p-8 bg-slate-50 border-t border-slate-100 flex gap-4">
                                    <button type="button" onclick="closeOfferModal()"
                                        class="w-full px-8 py-5 rounded-2xl font-black text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all uppercase tracking-widest text-sm">
                                        Fermer
                                    </button>
                                </div>
                            @else
                            </div>
                            <div class="p-8 bg-slate-50 border-t border-slate-100 flex gap-4">
                                <button type="button" onclick="closeOfferModal()"
                                    class="w-full px-8 py-5 rounded-2xl font-black text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all uppercase tracking-widest text-sm">
                                    Fermer
                                </button>
                            </div>
                        @endif
                    @else
            </div>
            <div class="p-8 bg-slate-50 border-t border-slate-100 flex gap-4">
                <button type="button" onclick="closeOfferModal()"
                    class="flex-1 px-8 py-5 rounded-2xl font-black text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all uppercase tracking-widest text-sm">
                    Fermer
                </button>
                <a href="{{ route('login') }}"
                    class="flex-[2] bg-indigo-600 text-white px-8 py-5 rounded-2xl font-black hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                    Se connecter pour postuler
                </a>
            </div>
        @endauth
    </form>
    </div>
    </div>

    @push('scripts')
        <script>
            const allOffres = @json($offres->items());
            const appliedOfferIds = @json($appliedOfferIds);

            function showOfferDetails(id) {
                const offre = allOffres.find(o => o.id == id);
                if (!offre) return;

                document.getElementById('modal-title').textContent = offre.titre;
                document.getElementById('modal-company').textContent = offre.recruteur ? offre.recruteur.nom_entreprise : 'Entreprise Confidentielle';
                document.getElementById('modal-description').textContent = offre.description.replace(/(<([^>]+)>)/gi, "");
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

                const postulerSection = document.getElementById('modal-postuler-section');
                const appliedMsg = document.getElementById('modal-already-applied-msg');
                const submitFooter = document.getElementById('modal-submit-footer');
                const closeOnlyFooter = document.getElementById('modal-close-only-footer');

                if (postulerSection && appliedMsg) {
                    if (appliedOfferIds.includes(id)) {
                        postulerSection.classList.add('hidden');
                        submitFooter.classList.add('hidden');

                        appliedMsg.classList.remove('hidden');
                        closeOnlyFooter.classList.remove('hidden');
                    } else {
                        postulerSection.classList.remove('hidden');
                        submitFooter.classList.remove('hidden');

                        appliedMsg.classList.add('hidden');
                        closeOnlyFooter.classList.add('hidden');
                    }
                }

                document.getElementById('offerModal').classList.remove('hidden');
            }

            function closeOfferModal() {
                document.getElementById('offerModal').classList.add('hidden');
            }
        </script>
        </script>
    @endpush

@endsection