@extends('layouts.app')

@section('title', 'RecruitPro - Trouvez votre prochain défi professionnel')

@section('content')
<div class="bg-white">
    {{-- Hero Section --}}
    <section class="relative overflow-hidden pt-20 pb-24 lg:pt-32 lg:pb-40 bg-[#fcfdfe]">
        {{-- Background gradients --}}
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-indigo-50/50 to-transparent"></div>
        <div class="absolute bottom-0 left-0 w-1/3 h-1/2 bg-gradient-to-tr from-purple-50/30 to-transparent"></div>
        
        <div class="container mx-auto px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-24">
                <div class="flex-1 text-center lg:text-left space-y-10 animate__animated animate__fadeInLeft">
                    <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-white border border-indigo-100 shadow-xl shadow-indigo-100/50 text-indigo-600 text-xs font-black uppercase tracking-[0.2em]">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-indigo-600"></span>
                        </span>
                        Le futur du recrutement au Burkina Faso
                    </div>
                    
                    <h1 class="text-6xl lg:text-8xl font-black text-slate-900 tracking-tighter leading-[0.9] lg:leading-[0.9]">
                        Trouvez le <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-indigo-800">talent</span> idéal.
                    </h1>
                    
                    <p class="text-2xl text-slate-500 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-medium opacity-80">
                        La plateforme de recrutement nouvelle génération qui connecte les entreprises visionnaires avec les talents les plus prometteurs du pays.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-5 justify-center lg:justify-start pt-4">
                        <a href="{{ route('public.offres') }}" class="group bg-indigo-600 text-white px-10 py-5 rounded-[2rem] font-black text-lg shadow-2xl shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-1.5 transition-all active:scale-95 flex items-center justify-center gap-3">
                            Découvrir les Offres
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="{{ route('register.choice') }}" class="bg-white text-slate-700 border-2 border-slate-100 px-10 py-5 rounded-[2rem] font-black text-lg hover:border-indigo-100 hover:text-indigo-600 hover:-translate-y-1.5 transition-all active:scale-95 flex items-center justify-center gap-3">
                            <i class="fas fa-building text-indigo-400"></i>
                            Recruter
                        </a>
                    </div>

                    <div class="flex items-center justify-center lg:justify-start gap-12 pt-12 border-t border-slate-100">
                        <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                            <p class="text-4xl font-black text-slate-900 tracking-tighter">12k<span class="text-indigo-500">+</span></p>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mt-1">Offres Actives</p>
                        </div>
                        <div class="w-px h-12 bg-slate-100"></div>
                        <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                            <p class="text-4xl font-black text-slate-900 tracking-tighter">500<span class="text-indigo-500">+</span></p>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mt-1">Entreprises</p>
                        </div>
                        <div class="w-px h-12 bg-slate-100"></div>
                        <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.6s">
                            <p class="text-4xl font-black text-slate-900 tracking-tighter">98<span class="text-indigo-500">%</span></p>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mt-1">Succès</p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 relative w-full max-w-2xl mx-auto lg:mx-0 animate__animated animate__fadeInRight">
                    <div class="relative z-10 rounded-[4rem] overflow-hidden shadow-[0_50px_100px_-20px_rgba(99,102,241,0.25)] border-[12px] border-white group">
                        <img src="{{ asset('images/welcome-hero.jpg') }}" alt="Recrutement" class="w-full h-[650px] object-cover group-hover:scale-110 transition-transform duration-[2s]">
                        <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-10 left-10 right-10 bg-white/10 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white/20 shadow-2xl">
                            <div class="flex items-center gap-6">
                                <div class="w-16 h-16 rounded-[1.5rem] bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/50">
                                    <i class="fas fa-quote-left text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-white text-lg font-bold italic opacity-90 leading-tight">"RecruitPro a transformé notre vision du recrutement."</p>
                                    <p class="text-indigo-200 text-sm font-black uppercase tracking-widest mt-2">Direction RH, Burkina Tech</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Decorative elements --}}
                    <div class="absolute -top-12 -right-12 w-64 h-64 bg-indigo-200/30 rounded-full blur-3xl animate-pulse"></div>
                    <div class="absolute -bottom-12 -left-12 w-64 h-64 bg-purple-200/30 rounded-full blur-3xl animate-pulse delay-700"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Trusted Companies --}}
    <section class="py-20 bg-slate-50/50">
        <div class="container mx-auto px-6">
            <p class="text-center text-sm font-black text-slate-400 uppercase tracking-[0.3em] mb-12">Ils nous font confiance</p>
            <div class="flex flex-wrap justify-center items-center gap-12 lg:gap-24 opacity-50 grayscale hover:grayscale-0 transition-all duration-700">
                <i class="fab fa-google text-4xl text-slate-400 hover:text-slate-600"></i>
                <i class="fab fa-microsoft text-4xl text-slate-400 hover:text-slate-600"></i>
                <i class="fab fa-amazon text-4xl text-slate-400 hover:text-slate-600"></i>
                <i class="fab fa-apple text-4xl text-slate-400 hover:text-slate-600"></i>
                <i class="fab fa-facebook text-4xl text-slate-400 hover:text-slate-600"></i>
                <i class="fab fa-airbnb text-4xl text-slate-400 hover:text-slate-600"></i>
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="py-32">
        <div class="container mx-auto px-6">
            <div class="max-w-3xl mx-auto text-center mb-20">
                <h2 class="text-4xl lg:text-5xl font-black text-slate-900 tracking-tighter mb-6">Pourquoi choisir <span class="text-indigo-600">RecruitPro</span> ?</h2>
                <p class="text-xl text-slate-500 font-medium leading-relaxed">Nous avons repensé le processus de recrutement pour le rendre plus humain, plus efficace et surtout plus performant.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="group p-10 rounded-[3rem] bg-white border border-slate-100 hover:border-indigo-100 hover:shadow-2xl hover:shadow-indigo-100 transition-all duration-500">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mb-8 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                        <i class="fas fa-magic"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 mb-4 tracking-tight">Matching Intelligent</h3>
                    <p class="text-slate-500 leading-relaxed font-medium">Notre algorithme IA analyse les compétences et la culture d'entreprise pour des connexions parfaites.</p>
                </div>

                <div class="group p-10 rounded-[3rem] bg-indigo-600 text-white shadow-2xl shadow-indigo-100 lg:-translate-y-8 transition-all duration-500">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 text-white flex items-center justify-center text-2xl mb-8 group-hover:scale-110 group-hover:bg-white group-hover:text-indigo-600 transition-all duration-500">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="text-xl font-black mb-4 tracking-tight">Recrutement Express</h3>
                    <p class="text-indigo-100 leading-relaxed font-medium">Réduisez votre temps de recrutement par 3 grâce à nos outils collaboratifs et automatisés.</p>
                </div>

                <div class="group p-10 rounded-[3rem] bg-white border border-slate-100 hover:border-indigo-100 hover:shadow-2xl hover:shadow-indigo-100 transition-all duration-500">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mb-8 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 mb-4 tracking-tight">Profils Vérifiés</h3>
                    <p class="text-slate-500 leading-relaxed font-medium">Fini les mauvaises surprises. Tous nos candidats passent par un processus de validation rigoureux.</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
