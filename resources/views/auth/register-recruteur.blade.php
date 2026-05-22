@extends('layouts.app')

@section('title', 'Inscription Recruteur - Plateforme de Recrutement')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full space-y-8">
            <div>
                <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-purple-100">
                    <i class="fas fa-building text-purple-600 text-xl"></i>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Créer un compte Recruteur
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Ou 
                    <a href="{{ route('register.choice') }}" class="font-medium text-purple-600 hover:text-purple-500">
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

            <form class="mt-8 space-y-6" action="{{ route('register.recruteur') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <!-- Informations personnelles -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-user mr-2"></i>Informations personnelles
                        </h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">
                                        Nom complet <span class="text-red-500">*</span>
                                    </label>
                                    <input id="name" name="name" type="text" required
                                        class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                        placeholder="Awa Sawadogo" value="{{ old('name') }}">
                                </div>
                                <div>
                                    <label for="profile_picture" class="block text-sm font-medium text-gray-700">
                                        Photo de profil
                                    </label>
                                    <input id="profile_picture" name="profile_picture" type="file" accept="image/*"
                                        class="mt-1 block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                </div>
                            </div>

                             <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    Adresse email <span class="text-red-500">*</span>
                                </label>
                                <input id="email" name="email" type="email" autocomplete="email" required
                                    class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                    placeholder="awa.sawadogo@entreprise.bf" value="{{ old('email') }}">
                            </div>

                            <div>
                                <label for="telephone" class="block text-sm font-medium text-gray-700">
                                    Téléphone
                                </label>
                                <input id="telephone" name="telephone" type="tel"
                                    class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                    placeholder="+226 70 00 00 00" value="{{ old('telephone') }}">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">
                                        Mot de passe <span class="text-red-500">*</span>
                                    </label>
                                    <input id="password" name="password" type="password" required
                                        class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                        placeholder="••••••••">
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                        Confirmer <span class="text-red-500">*</span>
                                    </label>
                                    <input id="password_confirmation" name="password_confirmation" type="password" required
                                        class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                        placeholder="••••••••">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="ville" class="block text-sm font-medium text-gray-700">
                                        Ville
                                    </label>
                                    <input id="ville" name="ville" type="text"
                                        class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                        placeholder="Ouagadougou" value="{{ old('ville') }}">
                                </div>
                                <div>
                                    <label for="pays" class="block text-sm font-medium text-gray-700">
                                        Pays
                                    </label>
                                    <input id="pays" name="pays" type="text"
                                        class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                        placeholder="Burkina Faso" value="{{ old('pays') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations entreprise -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-building mr-2"></i>Informations entreprise
                        </h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="nom_entreprise" class="block text-sm font-medium text-gray-700">
                                        Nom de l'entreprise <span class="text-red-500">*</span>
                                    </label>
                                    <input id="nom_entreprise" name="nom_entreprise" type="text" required
                                        class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                        placeholder="Burkina Digital SA" value="{{ old('nom_entreprise') }}">
                                </div>
                                <div>
                                    <label for="logo_entreprise" class="block text-sm font-medium text-gray-700">
                                        Logo entreprise
                                    </label>
                                    <input id="logo_entreprise" name="logo_entreprise" type="file" accept="image/*"
                                        class="mt-1 block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="secteur_activite" class="block text-sm font-medium text-gray-700">
                                        Secteur d'activité
                                    </label>
                                    <input id="secteur_activite" name="secteur_activite" type="text"
                                        class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                        placeholder="Informatique" value="{{ old('secteur_activite') }}">
                                </div>

                                <div>
                                    <label for="taille_entreprise" class="block text-sm font-medium text-gray-700">
                                        Taille entreprise <span class="text-red-500">*</span>
                                    </label>
                                    <select id="taille_entreprise" name="taille_entreprise" required
                                        class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 text-gray-900 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                        <option value="">Sélectionner...</option>
                                        <option value="startup">Startup</option>
                                        <option value="pme">PME</option>
                                        <option value="grande_entreprise">Grande entreprise</option>
                                        <option value="multinationale">Multinationale</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="site_web" class="block text-sm font-medium text-gray-700">
                                    Site web
                                </label>
                                <input id="site_web" name="site_web" type="url"
                                    class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                    placeholder="https://www.entreprise.com" value="{{ old('site_web') }}">
                            </div>

                            <div>
                                <label for="description_ent" class="block text-sm font-medium text-gray-700">
                                    Description entreprise
                                </label>
                                <textarea id="description_ent" name="description_ent" rows="3"
                                    class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                    placeholder="Décrivez votre entreprise et vos valeurs...">{{ old('description_ent') }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="contact_rh_email" class="block text-sm font-medium text-gray-700">
                                        Email RH
                                    </label>
                                    <input id="contact_rh_email" name="contact_rh_email" type="email"
                                        class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                        placeholder="rh@entreprise.bf" value="{{ old('contact_rh_email') }}">
                                </div>

                                <div>
                                    <label for="numero_rc" class="block text-sm font-medium text-gray-700">
                                        Numéro RC
                                    </label>
                                    <input id="numero_rc" name="numero_rc" type="text"
                                        class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                        placeholder="RC-123456" value="{{ old('numero_rc') }}">
                                </div>
                            </div>

                            <div class="bg-blue-50 p-3 rounded-lg">
                                <p class="text-sm text-blue-800">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Note :</strong> Votre compte sera créé avec un statut de vérification "en attente". 
                                    Un administrateur devra valider votre entreprise avant que vous puissiez publier des offres.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required
                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                        J'accepte les <a href="#" class="text-purple-600 hover:text-purple-500">conditions d'utilisation</a> 
                        et la <a href="#" class="text-purple-600 hover:text-purple-500">politique de confidentialité</a>
                    </label>
                </div>

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-building text-purple-500 group-hover:text-purple-400"></i>
                        </span>
                        Créer mon compte
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Vous avez déjà un compte? 
                        <a href="{{ route('login') }}" class="font-medium text-purple-600 hover:text-purple-500">
                            Connectez-vous ici
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection
