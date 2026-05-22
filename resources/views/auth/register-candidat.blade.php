@extends('layouts.app')

@section('title', 'Inscription Candidat - Plateforme de Recrutement')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-blue-100">
                    <i class="fas fa-user-tie text-blue-600 text-xl"></i>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Créer un compte Candidat
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Ou 
                    <a href="{{ route('register.choice') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        choisissez un autre type de compte
                    </a>
                </p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form class="mt-8 space-y-6" action="{{ route('register.candidat') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="profile_picture" class="block text-sm font-medium text-gray-700">
                            Photo de profil
                        </label>
                        <input id="profile_picture" name="profile_picture" type="file" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG (Max 2Mo)</p>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nom complet <span class="text-red-500">*</span>
                        </label>
                        <input id="name" name="name" type="text" required
                            class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Moussa Traoré" value="{{ old('name') }}">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Adresse email <span class="text-red-500">*</span>
                        </label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="jean.dupont@email.com" value="{{ old('email') }}">
                    </div>

                    <div>
                        <label for="telephone" class="block text-sm font-medium text-gray-700">
                            Téléphone
                        </label>
                        <input id="telephone" name="telephone" type="tel"
                            class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="+226 70 00 00 00" value="{{ old('telephone') }}">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Mot de passe <span class="text-red-500">*</span>
                        </label>
                        <input id="password" name="password" type="password" required
                            class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="••••••••">
                        <p class="mt-1 text-xs text-gray-500">Minimum 8 caractères</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Confirmer le mot de passe <span class="text-red-500">*</span>
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="••••••••">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="ville" class="block text-sm font-medium text-gray-700">
                                Ville
                            </label>
                            <input id="ville" name="ville" type="text"
                                class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Ouagadougou" value="{{ old('ville') }}">
                        </div>

                        <div>
                            <label for="pays" class="block text-sm font-medium text-gray-700">
                                Pays
                            </label>
                            <input id="pays" name="pays" type="text"
                                class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Burkina Faso" value="{{ old('pays') }}">
                        </div>
                    </div>

                    <div>
                        <label for="titre_professionnel" class="block text-sm font-medium text-gray-700">
                            Titre professionnel
                        </label>
                        <input id="titre_professionnel" name="titre_professionnel" type="text"
                            class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Développeur Web Full Stack" value="{{ old('titre_professionnel') }}">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="niveau_experience" class="block text-sm font-medium text-gray-700">
                                Niveau d'expérience
                            </label>
                            <select id="niveau_experience" name="niveau_experience"
                                class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Sélectionner...</option>
                                <option value="debutant">Débutant</option>
                                <option value="junior">Junior</option>
                                <option value="confirme">Confirmé</option>
                                <option value="senior">Senior</option>
                                <option value="expert">Expert</option>
                            </select>
                        </div>

                        <div>
                            <label for="disponibilite" class="block text-sm font-medium text-gray-700">
                                Disponibilité
                            </label>
                            <select id="disponibilite" name="disponibilite"
                                class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Sélectionner...</option>
                                <option value="immediate">Immédiate</option>
                                <option value="1_mois">1 mois</option>
                                <option value="3_mois">3 mois</option>
                                <option value="6_mois">6 mois</option>
                                <option value="plus_6_mois">Plus de 6 mois</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="pretention_salariale" class="block text-sm font-medium text-gray-700">
                                Prétention salariale (FCFA)
                            </label>
                            <input id="pretention_salariale" name="pretention_salariale" type="number" step="1"
                                class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="500000" value="{{ old('pretention_salariale') }}">
                        </div>

                        <div>
                            <label for="linkedin_url" class="block text-sm font-medium text-gray-700">
                                Profil LinkedIn
                            </label>
                            <input id="linkedin_url" name="linkedin_url" type="url"
                                class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="https://linkedin.com/in/jean-dupont" value="{{ old('linkedin_url') }}">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="is_open_to_relocation" name="is_open_to_relocation" type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_open_to_relocation" class="ml-2 block text-sm text-gray-900">
                            Je suis ouvert à la relocalisation
                        </label>
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                        J'accepte les <a href="#" class="text-blue-600 hover:text-blue-500">conditions d'utilisation</a> 
                        et la <a href="#" class="text-blue-600 hover:text-blue-500">politique de confidentialité</a>
                    </label>
                </div>

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-user-plus text-blue-500 group-hover:text-blue-400"></i>
                        </span>
                        Créer mon compte
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Vous avez déjà un compte? 
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            Connectez-vous ici
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection
