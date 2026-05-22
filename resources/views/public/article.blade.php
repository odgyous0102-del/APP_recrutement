@extends('layouts.app')

@section('title', 'Article - RecruitPro')

@section('content')
<div class="bg-slate-50 min-h-screen pt-24 pb-20">
    <div class="container mx-auto px-6">
        <div class="max-w-3xl mx-auto">
            {{-- Navigation retour --}}
            <a href="{{ route('public.conseils') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors mb-8">
                <i class="fas fa-arrow-left"></i> Retour aux conseils
            </a>

            {{-- En-tête de l'article --}}
            <div class="mb-10 animate-fade-in">
                <div class="flex items-center gap-3 mb-6">
                    <span class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider">
                        Carrière
                    </span>
                    <span class="text-sm text-slate-400 font-bold flex items-center gap-1.5"><i class="far fa-calendar"></i> 28 Avr 2026</span>
                </div>
                
                <h1 class="text-4xl lg:text-5xl font-black text-slate-900 tracking-tighter mb-6 leading-tight">
                    Le guide complet pour réussir sa carrière en 2026
                </h1>
                
                <p class="text-xl text-slate-500 font-medium leading-relaxed mb-8">
                    Ceci est un article de démonstration. Dans un environnement de production, ce contenu serait généré dynamiquement à partir d'une base de données ou d'un CMS.
                </p>

                <div class="flex items-center gap-4 py-6 border-y border-slate-200">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-md">
                        RP
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-900">L'équipe RecruitPro</p>
                        <p class="text-xs font-bold text-slate-500">Experts en recrutement</p>
                    </div>
                    <div class="ml-auto flex items-center gap-2">
                        <button class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 transition-colors flex items-center justify-center" onclick="alert('Partage copié dans le presse-papier !')">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Image principale --}}
            <div class="rounded-[2rem] overflow-hidden mb-12 shadow-xl animate-slide-up" style="animation-delay: 0.1s;">
                <img src="{{ asset('images/conseils-featured.jpg') }}" alt="Article Cover" class="w-full h-80 lg:h-[400px] object-cover">
            </div>

            {{-- Contenu de l'article --}}
            <div class="prose prose-lg prose-slate prose-indigo max-w-none animate-slide-up" style="animation-delay: 0.2s;">
                <p>Bienvenue sur notre plateforme de démonstration. Ce faux article a pour but de vous montrer à quoi ressemblerait une page de contenu textuel sur RecruitPro.</p>
                
                <h3>L'importance des Soft Skills</h3>
                <p>Dans le monde du travail actuel, les compétences techniques (hard skills) ne suffisent plus. Les employeurs recherchent des collaborateurs capables de s'adapter, de communiquer efficacement et de travailler en équipe. Ces compétences comportementales, ou "soft skills", font souvent la différence lors d'un recrutement.</p>

                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-6 rounded-r-2xl my-8">
                    <p class="m-0 font-bold text-indigo-900">"L'intelligence émotionnelle est devenue le critère de sélection numéro 1 pour les postes à responsabilité." - Alain Diabaté</p>
                </div>

                <h3>Comment développer son réseau professionnel</h3>
                <ul>
                    <li><strong>Participez à des événements :</strong> Les salons et webinaires sont d'excellentes opportunités.</li>
                    <li><strong>Utilisez LinkedIn :</strong> Un profil à jour et actif est indispensable.</li>
                    <li><strong>Cultivez vos relations :</strong> Prenez régulièrement des nouvelles de vos anciens collègues.</li>
                </ul>

                <p>En suivant ces quelques conseils, vous serez mieux préparé pour affronter les défis du marché de l'emploi et construire une carrière solide et enrichissante.</p>
            </div>
            
            {{-- Footer d'article (Newsletter) --}}
            <div class="mt-16 bg-slate-900 rounded-[2rem] p-8 lg:p-12 text-center animate-slide-up" style="animation-delay: 0.3s;">
                <h3 class="text-2xl font-black text-white mb-4">Cet article vous a plu ?</h3>
                <p class="text-slate-400 mb-8 max-w-lg mx-auto">Inscrivez-vous à notre newsletter pour recevoir nos prochains conseils directement dans votre boîte mail.</p>
                <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto" onsubmit="event.preventDefault(); alert('Merci pour votre inscription !');">
                    <input type="email" placeholder="Votre email" class="flex-1 bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500" required>
                    <button type="submit" class="bg-indigo-600 text-white font-bold rounded-xl px-6 py-3 hover:bg-indigo-500 transition-colors">S'inscrire</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
