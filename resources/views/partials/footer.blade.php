{{-- Footer Component - Reusable across all pages --}}
<footer class="bg-slate-900 text-white pt-20">
    <!-- Main Footer Content -->
    <div class="container mx-auto px-6 pb-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
            <!-- Company Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-900/20">
                        <i class="fas fa-briefcase text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-black tracking-tighter uppercase">Recruit<span class="text-indigo-500">Pro</span></span>
                </div>
                <p class="text-slate-400 text-base leading-relaxed font-medium">
                    La plateforme de recrutement nouvelle génération qui transforme la manière dont les talents et les entreprises se rencontrent.
                </p>
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-indigo-600 hover:text-white transition-all duration-300">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-indigo-600 hover:text-white transition-all duration-300">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-indigo-600 hover:text-white transition-all duration-300">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>

            <!-- Pour les Candidats -->
            <div>
                <h3 class="text-sm font-black uppercase tracking-[0.2em] text-indigo-500 mb-8">Candidats</h3>
                <ul class="space-y-4">
                    <li><a href="{{ route('public.offres') }}" class="text-slate-400 hover:text-white text-base font-bold transition-colors flex items-center gap-2 group"><i class="fas fa-chevron-right text-xs text-indigo-600 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all"></i> Rechercher des offres</a></li>
                    <li><a href="{{ route('register.choice') }}" class="text-slate-400 hover:text-white text-base font-bold transition-colors flex items-center gap-2 group"><i class="fas fa-chevron-right text-xs text-indigo-600 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all"></i> Créer un profil</a></li>
                    <li><a href="{{ route('public.conseils') }}" class="text-slate-400 hover:text-white text-base font-bold transition-colors flex items-center gap-2 group"><i class="fas fa-chevron-right text-xs text-indigo-600 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all"></i> Conseils carrière</a></li>
                </ul>
            </div>

            <!-- Pour les Recruteurs -->
            <div>
                <h3 class="text-sm font-black uppercase tracking-[0.2em] text-indigo-500 mb-8">Recruteurs</h3>
                <ul class="space-y-4">
                    <li><a href="#" class="text-slate-400 hover:text-white text-base font-bold transition-colors flex items-center gap-2 group"><i class="fas fa-chevron-right text-xs text-indigo-600 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all"></i> Publier une offre</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white text-base font-bold transition-colors flex items-center gap-2 group"><i class="fas fa-chevron-right text-xs text-indigo-600 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all"></i> Solutions entreprises</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white text-base font-bold transition-colors flex items-center gap-2 group"><i class="fas fa-chevron-right text-xs text-indigo-600 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all"></i> Tarification</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="space-y-6">
                <h3 class="text-sm font-black uppercase tracking-[0.2em] text-indigo-500 mb-8">Newsletter</h3>
                <p class="text-slate-400 text-base font-medium">Restez informé des meilleures opportunités.</p>
                <div class="relative">
                    <input type="email" placeholder="Votre email" class="w-full bg-slate-800 border border-slate-700 rounded-2xl px-5 py-4 text-base text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all">
                    <button class="absolute right-2 top-2 bottom-2 bg-indigo-600 text-white px-4 rounded-xl hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Footer -->
    <div class="border-t border-slate-800/50 py-8">
        <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6 text-sm font-bold text-slate-500 uppercase tracking-widest">
            <div class="flex flex-col md:flex-row items-center gap-4 md:gap-8">
                <p>© {{ date('Y') }} RecruitPro. Tous droits réservés.</p>
                <div class="flex items-center gap-4">
                    <span><i class="fas fa-map-marker-alt text-indigo-500 mr-2"></i> Ouagadougou, Burkina Faso</span>
                    <span><i class="fas fa-phone-alt text-indigo-500 mr-2"></i> +226 25 30 00 00</span>
                </div>
            </div>
            <div class="flex gap-8">
                <a href="#" class="hover:text-white transition-colors">Confidentialité</a>
                <a href="#" class="hover:text-white transition-colors">CGU</a>
                <a href="#" class="hover:text-white transition-colors">Mentions Légales</a>
            </div>
        </div>
    </div>
</footer>

<script>
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
