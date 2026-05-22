@extends('layouts.app')

@section('title', 'Conseils et Carrière - RecruitPro')

@section('content')
{{-- Background Decorators --}}
<div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-indigo-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-pulse"></div>
    <div class="absolute top-[20%] right-[-5%] w-72 h-72 bg-purple-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-pulse delay-700"></div>
</div>

<div class="relative z-10 bg-slate-50/80 backdrop-blur-3xl min-h-screen pt-24 pb-20">
    <div class="container mx-auto px-6">
        
        {{-- Hero Section Premium --}}
        <div class="max-w-4xl mx-auto text-center mb-20 animate-fade-in relative">
            <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-white shadow-xl shadow-indigo-100/50 border border-indigo-50 text-indigo-600 text-xs font-black uppercase tracking-widest mb-8 hover:-translate-y-1 transition-transform cursor-default">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-600"></span>
                </span>
                Le Hub Carrière
            </div>
            <h1 class="text-5xl lg:text-7xl font-black text-slate-900 tracking-tighter mb-8 leading-tight">
                Accélérez votre <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Trajectoire</span>
            </h1>
            <p class="text-xl text-slate-500 font-medium leading-relaxed max-w-2xl mx-auto">
                Des insights pointus, des stratégies éprouvées et des outils concrets pour dominer le marché de l'emploi au Burkina Faso.
            </p>
        </div>

        {{-- Filtres de catégories Interactifs --}}
        <div class="flex flex-wrap justify-center gap-3 mb-16 animate-fade-in">
            <button class="px-8 py-3 rounded-2xl bg-indigo-600 text-white font-black text-sm shadow-xl shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">Tout explorer</button>
            <button class="px-8 py-3 rounded-2xl bg-white text-slate-600 font-bold text-sm shadow-sm border border-slate-100 hover:border-indigo-200 hover:text-indigo-600 hover:-translate-y-0.5 transition-all group">
                <i class="fas fa-file-signature text-slate-400 group-hover:text-indigo-500 mr-2 transition-colors"></i> CV & Lettre
            </button>
            <button class="px-8 py-3 rounded-2xl bg-white text-slate-600 font-bold text-sm shadow-sm border border-slate-100 hover:border-indigo-200 hover:text-indigo-600 hover:-translate-y-0.5 transition-all group">
                <i class="fas fa-user-tie text-slate-400 group-hover:text-indigo-500 mr-2 transition-colors"></i> Entretiens
            </button>
            <button class="px-8 py-3 rounded-2xl bg-white text-slate-600 font-bold text-sm shadow-sm border border-slate-100 hover:border-indigo-200 hover:text-indigo-600 hover:-translate-y-0.5 transition-all group">
                <i class="fas fa-rocket text-slate-400 group-hover:text-indigo-500 mr-2 transition-colors"></i> Évolution
            </button>
        </div>

        {{-- Article à la une (Featured) --}}
        <div class="mb-16 animate-slide-up">
            <article onclick="window.location.href='{{ route('public.article', 'leadership-2026') }}'" class="bg-white rounded-[3rem] overflow-hidden shadow-2xl shadow-indigo-100/50 border border-slate-100 flex flex-col lg:flex-row group cursor-pointer hover:border-indigo-100 transition-colors">
                <div class="lg:w-1/2 h-64 lg:h-auto relative overflow-hidden">
                    <img src="{{ asset('images/conseils-featured.jpg') }}" alt="Leadership" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute top-6 left-6 bg-indigo-600 text-white px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider shadow-lg shadow-indigo-900/20">
                        À la une
                    </div>
                </div>
                <div class="lg:w-1/2 p-10 lg:p-16 flex flex-col justify-center">
                    <div class="flex items-center gap-4 text-sm text-slate-400 font-bold mb-6">
                        <span class="flex items-center gap-1.5 bg-slate-50 px-3 py-1 rounded-lg"><i class="far fa-calendar text-indigo-500"></i> 28 Avr 2026</span>
                        <span class="flex items-center gap-1.5 bg-slate-50 px-3 py-1 rounded-lg"><i class="far fa-clock text-indigo-500"></i> 10 min de lecture</span>
                    </div>
                    <h2 class="text-3xl lg:text-4xl font-black text-slate-900 mb-6 group-hover:text-indigo-600 transition-colors leading-tight">
                        Leadership 2026 : Les "Soft Skills" qui propulsent les cadres dirigeants
                    </h2>
                    <p class="text-slate-500 font-medium text-lg leading-relaxed mb-8">
                        L'expertise technique ne suffit plus. Découvrez comment l'intelligence émotionnelle, l'adaptabilité et la communication asynchrone sont devenus les critères numéro 1 des chasseurs de têtes.
                    </p>
                    <div class="flex items-center justify-between mt-auto">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-200">
                                AD
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-900">Alain Diabaté</p>
                                <p class="text-xs font-bold text-slate-400">Directeur Stratégie RH</p>
                            </div>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shadow-sm">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        {{-- Grille d'articles standard --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
            {{-- Article 1 --}}
            <article onclick="window.location.href='{{ route('public.article', 'cv-parfait-2026') }}'" class="bg-white rounded-[2rem] overflow-hidden shadow-sm border border-slate-100 hover:shadow-2xl hover:shadow-indigo-100/50 hover:-translate-y-2 transition-all duration-500 group cursor-pointer animate-slide-up" style="animation-delay: 0.1s;">
                <div class="h-56 bg-slate-200 relative overflow-hidden">
                    <img src="{{ asset('images/conseils-cv.jpg') }}" alt="Rédiger un CV" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-xl text-xs font-black text-slate-800 uppercase tracking-wider shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-blue-500 inline-block mr-1"></span> CV & Lettre
                    </div>
                </div>
                <div class="p-8">
                    <h3 class="text-xl font-black text-slate-900 mb-4 group-hover:text-indigo-600 transition-colors leading-snug">
                        Le "CV Parfait" en 2026 : Design, Mots-clés et IA
                    </h3>
                    <p class="text-slate-500 line-clamp-3 font-medium text-sm leading-relaxed mb-6">
                        Les attentes des recruteurs évoluent. Découvrez les sections indispensables, le design idéal et les mots-clés à intégrer pour passer les filtres ATS.
                    </p>
                    <div class="flex justify-between items-center border-t border-slate-100 pt-6">
                        <span class="text-xs font-bold text-slate-400">5 min de lecture</span>
                        <span class="text-indigo-600 font-bold text-sm flex items-center gap-1 group-hover:gap-2 transition-all">Lire <i class="fas fa-arrow-right"></i></span>
                    </div>
                </div>
            </article>

            {{-- Article 2 --}}
            <article onclick="window.location.href='{{ route('public.article', 'questions-pieges-entretien') }}'" class="bg-white rounded-[2rem] overflow-hidden shadow-sm border border-slate-100 hover:shadow-2xl hover:shadow-indigo-100/50 hover:-translate-y-2 transition-all duration-500 group cursor-pointer animate-slide-up" style="animation-delay: 0.2s;">
                <div class="h-56 bg-slate-200 relative overflow-hidden">
                    <img src="{{ asset('images/conseils-entretien.jpg') }}" alt="Préparation entretien" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-xl text-xs font-black text-slate-800 uppercase tracking-wider shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block mr-1"></span> Entretien
                    </div>
                </div>
                <div class="p-8">
                    <h3 class="text-xl font-black text-slate-900 mb-4 group-hover:text-indigo-600 transition-colors leading-snug">
                        Les 10 questions pièges en entretien et l'art d'y répondre
                    </h3>
                    <p class="text-slate-500 line-clamp-3 font-medium text-sm leading-relaxed mb-6">
                        "Quels sont vos défauts ?" "Où vous voyez-vous dans 5 ans ?" Apprenez à déjouer les questions classiques avec des réponses authentiques.
                    </p>
                    <div class="flex justify-between items-center border-t border-slate-100 pt-6">
                        <span class="text-xs font-bold text-slate-400">8 min de lecture</span>
                        <span class="text-indigo-600 font-bold text-sm flex items-center gap-1 group-hover:gap-2 transition-all">Lire <i class="fas fa-arrow-right"></i></span>
                    </div>
                </div>
            </article>

            {{-- CTA Newsletter Premium --}}
            <article class="bg-slate-900 rounded-[2rem] overflow-hidden shadow-2xl p-8 flex flex-col justify-center animate-slide-up relative group border border-slate-800 hover:border-indigo-500/50 transition-colors" style="animation-delay: 0.3s;">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/20 to-purple-600/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-500 rounded-full mix-blend-screen filter blur-[50px] opacity-50 animate-pulse"></div>
                
                <div class="relative z-10">
                    <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-indigo-400 text-xl mb-6 backdrop-blur-sm border border-white/10 group-hover:scale-110 transition-transform">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3">Newsletter VIP</h3>
                    <p class="text-slate-400 font-medium text-sm mb-8 leading-relaxed">
                        Recevez chaque lundi un concentré d'astuces exclusives et les meilleures offres avant tout le monde.
                    </p>
                    <form class="space-y-3" onsubmit="handleNewsletter(event)">
                        <div class="relative">
                            <i class="fas fa-at absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
                            <input type="email" id="vip-email" required placeholder="Votre email professionnel" class="w-full bg-slate-800/50 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm font-medium">
                        </div>
                        <button type="submit" id="vip-submit" class="w-full bg-indigo-600 text-white font-black rounded-xl px-4 py-3 hover:bg-indigo-500 transition-colors shadow-lg shadow-indigo-900/50 flex items-center justify-center gap-2">
                            <span>S'inscrire</span>
                        </button>
                    </form>
                </div>
            </article>
        </div>

        {{-- Section Multimédia (Podcasts/Vidéos) --}}
        <div class="mb-24">
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-3xl font-black text-slate-900">Webinaires & <span class="text-indigo-600">Masterclass</span></h2>
                <a href="#" class="hidden sm:flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors">
                    Tout voir <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div class="group relative rounded-[2.5rem] overflow-hidden cursor-pointer shadow-xl shadow-slate-200/50" onclick="openVideoModal('{{ asset('videos/sample.mp4') }}')">
                    <img src="{{ asset('images/conseils-masterclass.jpg') }}" alt="Masterclass" class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-2xl group-hover:scale-110 group-hover:bg-indigo-600 transition-all duration-300 border border-white/30 shadow-2xl">
                            <i class="fas fa-play ml-1"></i>
                        </div>
                    </div>
                    <div class="absolute bottom-8 left-8 right-8">
                        <div class="bg-rose-500 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-lg inline-block mb-3">Replay</div>
                        <h3 class="text-2xl font-black text-white mb-2 leading-tight">Négocier son salaire comme un pro</h3>
                        <p class="text-slate-300 text-sm font-medium">Par Ousmane Traoré - 45 min</p>
                    </div>
                </div>

                <div class="group relative rounded-[2.5rem] overflow-hidden cursor-pointer shadow-xl shadow-slate-200/50" onclick="openVideoModal('{{ asset('videos/sample.mp4') }}')">
                    <img src="{{ asset('images/conseils-podcast.jpg') }}" alt="Podcast" class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-2xl group-hover:scale-110 group-hover:bg-purple-600 transition-all duration-300 border border-white/30 shadow-2xl">
                            <i class="fas fa-podcast"></i>
                        </div>
                    </div>
                    <div class="absolute bottom-8 left-8 right-8">
                        <div class="bg-purple-500 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-lg inline-block mb-3">Podcast EP. 12</div>
                        <h3 class="text-2xl font-black text-white mb-2 leading-tight">Le marché de la Tech au Burkina</h3>
                        <p class="text-slate-300 text-sm font-medium">Invité : Sarah Kaboré - 32 min</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section Boîte à Outils Premium --}}
        <div class="relative bg-white rounded-[3rem] p-10 lg:p-16 border border-slate-100 shadow-2xl shadow-indigo-100/30 overflow-hidden">
            {{-- Deco absolue --}}
            <div class="absolute top-0 right-0 w-64 h-full bg-gradient-to-l from-indigo-50/50 to-transparent"></div>
            
            <div class="relative z-10 text-center mb-16 max-w-2xl mx-auto">
                <h2 class="text-3xl lg:text-4xl font-black text-slate-900 mb-6">La <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Boîte à outils</span> du candidat</h2>
                <p class="text-slate-500 font-medium text-lg">Des ressources téléchargeables et des outils interactifs conçus pour optimiser chaque étape de votre recherche d'emploi.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-6 relative z-10">
                <div class="bg-slate-50 p-8 rounded-[2rem] border border-slate-100 flex flex-col items-start hover:bg-white hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-100/50 hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 text-white flex items-center justify-center text-2xl mb-6 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-word"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-3">Templates CV & LM</h3>
                    <p class="text-slate-500 text-sm font-medium mb-8 leading-relaxed">15 modèles professionnels prêts à l'emploi, adaptés à différents secteurs d'activité au format Word.</p>
                    <button onclick="handleDownload(this)" class="w-full py-3 rounded-xl bg-white border-2 border-slate-100 text-slate-600 font-bold text-sm group-hover:border-indigo-100 group-hover:text-indigo-600 group-hover:bg-indigo-50 transition-colors mt-auto flex items-center justify-center gap-2">
                        <span>Télécharger le pack</span>
                    </button>
                </div>
                
                <div class="bg-slate-50 p-8 rounded-[2rem] border border-slate-100 flex flex-col items-start hover:bg-white hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-100/50 hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 text-white flex items-center justify-center text-2xl mb-6 shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-3">Simulateur Salarial</h3>
                    <p class="text-slate-500 text-sm font-medium mb-8 leading-relaxed">Évaluez précisément votre prétention salariale grâce à notre algorithme basé sur +5000 offres locales.</p>
                    <button onclick="alert('Le simulateur salarial sera disponible prochainement !')" class="w-full py-3 rounded-xl bg-white border-2 border-slate-100 text-slate-600 font-bold text-sm group-hover:border-indigo-100 group-hover:text-indigo-600 group-hover:bg-indigo-50 transition-colors mt-auto">
                        Estimer mon salaire
                    </button>
                </div>
                
                <div class="bg-slate-50 p-8 rounded-[2rem] border border-slate-100 flex flex-col items-start hover:bg-white hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-100/50 hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 to-blue-600 text-white flex items-center justify-center text-2xl mb-6 shadow-lg shadow-indigo-600/30 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-astronaut"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-3">Coaching Privé 1to1</h3>
                    <p class="text-slate-500 text-sm font-medium mb-8 leading-relaxed">Bénéficiez d'une session stratégique de 45 minutes avec l'un de nos experts en recrutement.</p>
                    <a href="{{ route('register.choice') }}" class="w-full py-3 rounded-xl bg-white border-2 border-slate-100 text-slate-600 font-bold text-sm group-hover:border-indigo-100 group-hover:text-indigo-600 group-hover:bg-indigo-50 transition-colors mt-auto text-center block">
                        Réserver un créneau
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Video Modal --}}
<div id="videoModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0 bg-slate-900/90 backdrop-blur-sm" onclick="closeVideoModal()"></div>
    <div class="relative w-full max-w-5xl mx-4 aspect-video bg-black rounded-2xl overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300" id="videoContainer">
        <button onclick="closeVideoModal()" class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
            <i class="fas fa-times"></i>
        </button>
        <video id="html5Player" controls class="w-full h-full" preload="auto">
            <source src="" type="video/mp4">
            Votre navigateur ne supporte pas la balise vidéo.
        </video>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Gérer l'inscription à la newsletter VIP
    function handleNewsletter(e) {
        e.preventDefault();
        const btn = document.getElementById('vip-submit');
        const email = document.getElementById('vip-email').value;
        const originalText = btn.innerHTML;
        
        // Simulation d'envoi
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Inscription...';
        btn.disabled = true;
        
        setTimeout(() => {
            btn.classList.remove('bg-indigo-600', 'hover:bg-indigo-500');
            btn.classList.add('bg-emerald-500', 'hover:bg-emerald-600');
            btn.innerHTML = '<i class="fas fa-check"></i> Inscrit avec succès !';
            document.getElementById('vip-email').value = '';
            
            // Notification toast (si existante ou native)
            alert(`Félicitations ! L'adresse ${email} a été ajoutée à notre liste VIP.`);
            
            setTimeout(() => {
                btn.classList.remove('bg-emerald-500', 'hover:bg-emerald-600');
                btn.classList.add('bg-indigo-600', 'hover:bg-indigo-500');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 3000);
        }, 1500);
    }

    // Gestion de la modal vidéo
    const modal = document.getElementById('videoModal');
    const container = document.getElementById('videoContainer');
    const player = document.getElementById('html5Player');

    function openVideoModal(url) {
        player.src = url;
        modal.classList.remove('hidden');
        // Un petit délai pour l'animation d'apparition
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            container.classList.remove('scale-95');
            player.play();
        }, 10);
        document.body.style.overflow = 'hidden'; // Empêche le scroll
    }

    function closeVideoModal() {
        modal.classList.add('opacity-0');
        container.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            player.pause();
            player.src = ''; // Arrête la vidéo
            document.body.style.overflow = ''; // Restaure le scroll
        }, 300);
    }

    // Simulation de téléchargement
    function handleDownload(btn) {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Préparation...';
        btn.disabled = true;
        
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-check text-emerald-500"></i> Téléchargé';
            // Créer un faux lien de téléchargement
            const a = document.createElement('a');
            a.href = '#';
            a.download = 'templates-cv.zip';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }, 2000);
        }, 1500);
    }
</script>
@endpush
