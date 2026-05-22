@extends('layouts.app')

@section('title', 'Inscription - Plateforme de Recrutement')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full space-y-8">
            <div>
                <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-blue-100">
                    <i class="fas fa-briefcase text-blue-600 text-xl"></i>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Rejoignez notre plateforme
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Choisissez votre type de compte pour commencer
                </p>
            </div>

            <div class="mt-8 grid md:grid-cols-2 gap-8">
                <!-- Carte Candidat -->
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="text-center">
                        <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-blue-100 mb-4">
                            <i class="fas fa-user-tie text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Je suis un Candidat</h3>
                        <p class="text-gray-600 mb-6">
                            Trouvez l'emploi de vos rêves, postulez aux offres et suivez vos candidatures
                        </p>
                        <ul class="text-left text-sm text-gray-600 mb-6 space-y-2">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Accès à toutes les offres d'emploi
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Profil professionnel personnalisé
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Suivi des candidatures en temps réel
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Notifications pour les nouvelles offres
                            </li>
                        </ul>
                        <a href="{{ route('register.candidat') }}" 
                           class="w-full bg-blue-600 text-white py-3 px-4 rounded-md font-medium hover:bg-blue-700 transition-colors duration-200 inline-block">
                            S'inscrire comme Candidat
                        </a>
                    </div>
                </div>

                <!-- Carte Recruteur -->
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="text-center">
                        <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-purple-100 mb-4">
                            <i class="fas fa-building text-purple-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Je suis un Recruteur</h3>
                        <p class="text-gray-600 mb-6">
                            Publiez vos offres, trouvez les meilleurs talents et gérez vos recrutements
                        </p>
                        <ul class="text-left text-sm text-gray-600 mb-6 space-y-2">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Publication d'offres d'emploi
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Accès à la base de candidats
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Gestion des entretiens et évaluations
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Tableau de bord statistiques
                            </li>
                        </ul>
                        <a href="{{ route('register.recruteur') }}"
                           class="w-full bg-purple-600 text-white py-3 px-4 rounded-md font-medium hover:bg-purple-700 transition-colors duration-200 inline-block">
                            S'inscrire comme Recruteur
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Vous avez déjà un compte? 
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Connectez-vous ici
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
