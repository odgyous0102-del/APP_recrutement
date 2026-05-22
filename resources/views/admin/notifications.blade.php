@extends('layouts.app')

@section('title', 'Gestion des Notifications Système - RecruitPro')

@section('content')
<div class="flex h-screen bg-[#f8fafc] overflow-hidden">
    {{-- Barre latérale --}}
    @include('admin.partials.sidebar', ['active' => 'notifications'])

    {{-- Contenu principal --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Barre supérieure --}}
        @include('admin.partials.header', ['title' => 'Notifications Système'])

        {{-- Scrollable content --}}
        <main class="flex-1 overflow-y-auto p-10 space-y-10 animate__animated animate__fadeIn">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                {{-- Formulaire d'envoi --}}
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden h-fit">
                    <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                        <h4 class="text-lg font-black text-gray-900 uppercase tracking-tighter">Envoyer une notification</h4>
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                    </div>
                    <div class="p-8">
                        <form action="{{ route('admin.send_notification') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Destinataire</label>
                                <select name="user_id" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all select2">
                                    <option value="all">Tous les utilisateurs actifs</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ ucfirst($user->role) }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Titre de la notification</label>
                                <input type="text" name="titre" required placeholder="Ex: Maintenance prévue, Nouveau service..." 
                                    class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>

                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Message</label>
                                <textarea name="message" rows="5" required placeholder="Saisissez votre message ici..."
                                    class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 transition-all resize-none"></textarea>
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 flex items-center justify-center gap-3 group">
                                <span>Diffuser la notification</span>
                                <i class="fas fa-broadcast-tower group-hover:animate-pulse"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Historique récent --}}
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden h-fit">
                    <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                        <h4 class="text-lg font-black text-gray-900 uppercase tracking-tighter">Diffusions Récentes</h4>
                        <span class="text-[10px] font-black text-indigo-500 bg-indigo-50 px-3 py-1 rounded-lg uppercase tracking-widest">Logs</span>
                    </div>
                    <div class="p-8 space-y-6 max-h-[600px] overflow-y-auto custom-scrollbar">
                        @forelse($recentNotifications as $notif)
                            <div class="p-6 bg-gray-50 rounded-[2rem] border border-gray-100 hover:border-indigo-200 transition-all">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h5 class="font-black text-gray-900 tracking-tight">{{ $notif->titre }}</h5>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">
                                            Envoyé à : {{ $notif->user->name }}
                                        </p>
                                    </div>
                                    <span class="text-[10px] text-gray-400 font-medium">{{ $notif->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $notif->message }}</p>
                            </div>
                        @empty
                            <div class="text-center py-20">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-bell-slash text-gray-200 text-2xl"></i>
                                </div>
                                <p class="text-gray-400 font-medium italic">Aucune notification diffusée récemment.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
</style>
@endsection
