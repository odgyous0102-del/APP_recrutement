{{-- Header Professionnel - Plateforme de Recrutement --}}
<header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100 shadow-sm">
    {{-- Top Bar - Informations de Contact --}}
    <div class="bg-slate-900 text-slate-400 py-2 hidden md:block">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center text-xs font-bold uppercase tracking-widest">
                <div class="flex items-center gap-6">
                    <span class="flex items-center gap-2 hover:text-white transition-colors cursor-default">
                        <i class="fas fa-phone-alt text-indigo-400"></i>
                        +226 25 30 00 00
                    </span>
                    <span class="flex items-center gap-2 hover:text-white transition-colors cursor-default">
                        <i class="fas fa-envelope text-indigo-400"></i>
                        contact@recruitpro.bf
                    </span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-slate-500">Suivez-nous :</span>
                    <a href="#" class="hover:text-white transition-colors"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="hover:text-white transition-colors"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-white transition-colors"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation Principale --}}
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-center py-4">
            {{-- Logo Section --}}
            <div class="flex items-center">
                <a href="{{ route('welcome') }}" class="flex items-center gap-4 group">
                    <div
                        class="relative w-14 h-14 overflow-hidden rounded-2xl bg-white flex items-center justify-center group-hover:scale-105 transition-all duration-300 shadow-sm border border-slate-100 p-2 ring-4 ring-indigo-50/50">
                        <img src="{{ asset('images/logo.png') }}" alt="RecruitPro Logo"
                            class="w-full h-full object-contain">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-black text-slate-800 tracking-tighter leading-none">RECRUIT<span
                                class="text-indigo-600">PRO</span></span>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Talent
                            Hunter</span>
                    </div>
                </a>
            </div>

            {{-- Liens de Navigation --}}
            <nav class="hidden lg:flex items-center gap-8">
                <a href="{{ route('welcome') }}"
                    class="text-[15px] font-bold text-slate-600 hover:text-indigo-600 transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-indigo-600 hover:after:w-full after:transition-all">
                    Accueil
                </a>
                <a href="{{ route('public.offres') }}"
                    class="text-[15px] font-bold text-slate-600 hover:text-indigo-600 transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-indigo-600 hover:after:w-full after:transition-all">
                    Offres d'emploi
                </a>
                <a href="{{ route('public.entreprises') }}"
                    class="text-[15px] font-bold text-slate-600 hover:text-indigo-600 transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-indigo-600 hover:after:w-full after:transition-all">
                    Entreprises
                </a>
                <a href="{{ route('public.conseils') }}"
                    class="text-[15px] font-bold text-slate-600 hover:text-indigo-600 transition-all relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-indigo-600 hover:after:w-full after:transition-all">
                    Conseils Carrière
                </a>
            </nav>

            {{-- Actions Utilisateur --}}
            <div class="flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}"
                        class="hidden sm:block text-sm font-bold text-slate-600 hover:text-indigo-600 px-4 py-2 transition-colors">
                        Connexion
                    </a>
                    <a href="{{ route('register.choice') }}"
                        class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all active:scale-95">
                        Nous Rejoindre
                    </a>
                @else
                    {{-- Notifications --}}
                    <div class="relative group mr-2" id="notificationDropdown">
                        <button
                            class="w-10 h-10 rounded-xl bg-slate-50 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-all flex items-center justify-center relative">
                            <i class="fas fa-bell"></i>
                            @if(count($unreadNotifications ?? []) > 0)
                                <span
                                    class="absolute top-2.5 right-2.5 w-2 h-2 bg-rose-500 rounded-full border-2 border-white animate-pulse"></span>
                            @endif
                        </button>

                        {{-- Dropdown de notifications --}}
                        <div
                            class="absolute right-0 mt-3 w-80 bg-white rounded-3xl shadow-2xl border border-slate-100 py-4 opacity-0 invisible translate-y-2 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 z-[101]">
                            <div class="px-5 pb-3 border-b border-slate-50 flex justify-between items-center">
                                <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest">Notifications</h4>
                                @if(count($unreadNotifications ?? []) > 0)
                                    <form action="{{ route('notifications.markAllRead') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="text-xs font-bold text-indigo-500 hover:text-indigo-700 transition-colors">Tout
                                            lire</button>
                                    </form>
                                @endif
                            </div>

                            <div class="max-h-80 overflow-y-auto custom-scrollbar">
                                @forelse($unreadNotifications ?? [] as $notification)
                                    <div
                                        class="px-5 py-4 hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0 relative group/item">
                                        <div class="flex gap-3">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-indigo-50 flex-shrink-0 flex items-center justify-center text-indigo-500">
                                                @if($notification->type == 'nouvelle_candidature') <i
                                                    class="fas fa-file-alt text-xs"></i>
                                                @elseif($notification->type == 'entretien_planifie') <i
                                                    class="fas fa-calendar-check text-xs"></i>
                                                @elseif($notification->type == 'system_broadcast') <i
                                                    class="fas fa-bullhorn text-xs"></i>
                                                @else <i class="fas fa-bell text-xs"></i>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-[13px] font-bold text-slate-800 leading-snug">
                                                    {{ $notification->titre }}</p>
                                                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                                    {{ $notification->message }}</p>
                                                <p class="text-xs text-slate-400 mt-2 font-medium">
                                                    {{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <form action="{{ route('notifications.markRead', $notification) }}" method="POST"
                                            class="absolute top-4 right-4 opacity-0 group-hover/item:opacity-100 transition-opacity">
                                            @csrf
                                            <button type="submit" title="Marquer comme lu"
                                                class="w-6 h-6 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm">
                                                <i class="fas fa-check text-[8px]"></i>
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <div class="px-5 py-10 text-center">
                                        <div
                                            class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-bell-slash text-slate-200 text-lg"></i>
                                        </div>
                                        <p class="text-xs font-bold text-slate-400 italic">Aucune nouvelle notification</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Profil Utilisateur --}}
                    <div class="relative group">
                        <button
                            class="flex items-center gap-3 p-1 pr-3 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-white hover:border-indigo-100 transition-all">
                            <div
                                class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black text-sm overflow-hidden border border-slate-100">
                                @if(Auth::user()->isRecruteur() && Auth::user()->recruteur && Auth::user()->recruteur->logo_entreprise)
                                    <img src="{{ asset('storage/' . Auth::user()->recruteur->logo_entreprise) }}"
                                        class="w-full h-full object-cover"
                                        onerror="this.style.display='none'; this.parentElement.innerHTML='{{ substr(Auth::user()->name, 0, 1) }}';">
                                @elseif(Auth::user()->profile_picture)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                @endif
                            </div>
                            <div class="hidden sm:flex flex-col items-start">
                                <span class="text-xs font-black text-slate-800 leading-none">{{ Auth::user()->name }}</span>
                                <span
                                    class="text-[11px] font-bold text-slate-400 uppercase tracking-tighter">{{ Auth::user()->role }}</span>
                            </div>
                            <i
                                class="fas fa-chevron-down text-xs text-slate-400 group-hover:text-indigo-600 transition-colors"></i>
                        </button>

                        {{-- Menu Déroulant Premium --}}
                        <div
                            class="absolute right-0 mt-3 w-64 bg-white rounded-3xl shadow-2xl border border-slate-100 py-3 opacity-0 invisible translate-y-2 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 z-[100]">
                            <div class="px-5 py-3 border-b border-slate-50 mb-2">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Connecté en tant
                                    que</p>
                                <p class="text-sm font-black text-slate-800 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            @if(Auth::user()->isCandidat())
                                <a href="{{ route('candidat.dashboard') }}"
                                    class="flex items-center gap-3 px-5 py-3 text-sm font-bold text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <i class="fas fa-th-large w-5 text-indigo-400"></i> Tableau de bord
                                </a>
                                <a href="{{ route('candidat.dashboard') }}?tab=profile"
                                    class="flex items-center gap-3 px-5 py-3 text-sm font-bold text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <i class="fas fa-user-circle w-5 text-indigo-400"></i> Mon Profil
                                </a>
                                <a href="{{ route('candidat.dashboard') }}?tab=applications"
                                    class="flex items-center gap-3 px-5 py-3 text-sm font-bold text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <i class="fas fa-paper-plane w-5 text-indigo-400"></i> Mes Candidatures
                                </a>
                            @elseif(Auth::user()->isRecruteur())
                                <a href="{{ route('recruteur.dashboard') }}"
                                    class="flex items-center gap-3 px-5 py-3 text-sm font-bold text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <i class="fas fa-chart-line w-5 text-indigo-400"></i> Tableau de bord
                                </a>
                                <a href="{{ route('recruteur.dashboard') }}"
                                    class="flex items-center gap-3 px-5 py-3 text-sm font-bold text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <i class="fas fa-briefcase w-5 text-indigo-400"></i> Gérer mes offres
                                </a>
                            @endif

                            <div class="border-t border-slate-50 my-2"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-5 py-3 text-sm font-bold text-rose-500 hover:bg-rose-50 transition-colors">
                                    <i class="fas fa-sign-out-alt w-5"></i> Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest

                {{-- Mobile Toggle --}}
                <button onclick="toggleMobileMenu()"
                    class="lg:hidden w-12 h-12 rounded-2xl bg-slate-50 text-slate-600 flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 transition-all">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Navigation Menu --}}
    <div id="mobileMenu" class="hidden lg:hidden bg-white border-t border-slate-50 p-6 space-y-4 animate-fadeIn">
        <nav class="flex flex-col gap-4">
            <a href="{{ route('welcome') }}" class="text-lg font-bold text-slate-800">Accueil</a>
            <a href="{{ route('public.offres') }}" class="text-lg font-bold text-slate-800">Offres d'emploi</a>
            <a href="{{ route('public.entreprises') }}" class="text-lg font-bold text-slate-800">Entreprises</a>
            <a href="{{ route('public.conseils') }}" class="text-lg font-bold text-slate-800">Conseils Carrière</a>
        </nav>
        @guest
            <div class="flex flex-col gap-3 pt-4 border-t border-slate-50">
                <a href="{{ route('login') }}"
                    class="w-full py-4 text-center font-bold text-slate-600 rounded-2xl bg-slate-50">Connexion</a>
                <a href="{{ route('register.choice') }}"
                    class="w-full py-4 text-center font-bold text-white rounded-2xl bg-indigo-600 shadow-lg shadow-indigo-100">Nous
                    Rejoindre</a>
            </div>
        @endguest
    </div>
</header>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
    }
</script>