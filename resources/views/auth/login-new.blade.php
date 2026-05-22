@extends('layouts.app')

@section('title', 'Connexion - RecruitPro')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-purple-50 to-indigo-100">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-purple-600 shadow-lg">
                <i class="fas fa-user text-white text-xl"></i>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Connectez-vous à votre compte
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Ou 
                <a href="{{ route('register.choice') }}" class="font-medium text-purple-600 hover:text-purple-500">
                    créez un nouveau compte
                </a>
            </p>
        </div>

        <div class="bg-white shadow-xl rounded-lg p-8">
            <form class="space-y-6" action="{{ route('login.post') }}" method="POST">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Adresse email
                        </label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                placeholder="votre@email.com" value="{{ old('email') }}">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Mot de passe
                        </label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Se souvenir de moi
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-purple-600 hover:text-purple-500">
                            Mot de passe oublié?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt text-purple-500 group-hover:text-purple-400"></i>
                        </span>
                        Se connecter
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Ou continuer avec</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button type="button"
                        class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fab fa-google mr-2"></i>
                        Google
                    </button>

                    <button type="button"
                        class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fab fa-linkedin mr-2"></i>
                        LinkedIn
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
