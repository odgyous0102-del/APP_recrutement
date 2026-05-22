@extends('layouts.app')

@section('title', 'Messagerie - Admin')

@section('content')
<div class="flex h-screen bg-[#f8fafc] overflow-hidden">
    @include('admin.partials.sidebar', ['active' => 'messages'])

    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Header --}}
        @php
            $headerAction = '<button onclick="document.getElementById(\'newMessageModal\').classList.remove(\'hidden\')" 
                        class="bg-indigo-600 text-white px-8 py-3 rounded-2xl text-sm font-black shadow-2xl shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <i class="fas fa-plus"></i> Nouvelle Conversation
                </button>';
        @endphp
        @include('admin.partials.header', ['title' => 'Centre de Communication', 'extraAction' => $headerAction])

        {{-- Conversation Layout --}}
        <div class="flex-1 flex overflow-hidden">
            {{-- Conversations Sidebar --}}
            <aside class="w-96 border-r border-gray-100 bg-white flex flex-col shrink-0 overflow-hidden">
                <div class="p-6 border-b border-gray-50">
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="conversation-search" placeholder="Rechercher une conversation..." 
                               class="w-full pl-10 pr-4 py-3 bg-gray-50 border-none rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-100">
                    </div>
                </div>
                
                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($conversations as $interlocuteur_id => $messages)
                        @php
                            $lastMsg = $messages->last();
                            $otherUser = $lastMsg->expediteur_id == Auth::id() ? $lastMsg->destinataire : $lastMsg->expediteur;
                        @endphp
                        <button onclick="showConversation({{ $interlocuteur_id }})" 
                                class="w-full p-6 flex items-start gap-4 hover:bg-indigo-50/50 transition-colors border-b border-gray-50 text-left conversation-btn" 
                                data-user-id="{{ $interlocuteur_id }}">
                            <div class="relative shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($otherUser->name) }}&background=random" 
                                     class="w-12 h-12 rounded-2xl shadow-sm">
                                <span class="absolute -bottom-1 -right-1 w-4 h-4 {{ $otherUser->is_active ? 'bg-green-500' : 'bg-gray-300' }} border-2 border-white rounded-full"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-1">
                                    <h4 class="text-sm font-black text-gray-900 truncate">{{ $otherUser->name }}</h4>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase shrink-0">{{ $lastMsg->created_at->diffForHumans(null, true) }}</span>
                                </div>
                                <p class="text-xs text-gray-500 line-clamp-1 font-medium">
                                    {{ $lastMsg->expediteur_id == Auth::id() ? 'Vous: ' : '' }}{{ $lastMsg->contenu }}
                                </p>
                                <span class="inline-block mt-2 px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest {{ $otherUser->role == 'recruteur' ? 'bg-indigo-50 text-indigo-600' : 'bg-blue-50 text-blue-600' }}">
                                    {{ $otherUser->role }}
                                </span>
                            </div>
                        </button>
                    @empty
                        <div class="p-12 text-center text-gray-400 opacity-50">
                            <i class="fas fa-comments text-4xl mb-4"></i>
                            <p class="text-sm font-bold uppercase tracking-widest">Aucune discussion</p>
                        </div>
                    @endforelse
                </div>
            </aside>

            {{-- Conversation Content --}}
            <main class="flex-1 bg-gray-50/30 flex flex-col overflow-hidden relative">
                @foreach($conversations as $interlocuteur_id => $messages)
                    @php
                        $otherUser = $messages->last()->expediteur_id == Auth::id() ? $messages->last()->destinataire : $messages->last()->expediteur;
                    @endphp
                    <div id="conv-{{ $interlocuteur_id }}" class="conversation-content hidden flex flex-col h-full absolute inset-0 bg-[#fcfdfe]">
                        {{-- Chat Header --}}
                        <div class="p-6 border-b border-gray-100 bg-white flex items-center justify-between z-10 shrink-0">
                            <div class="flex items-center gap-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($otherUser->name) }}&background=random" 
                                     class="w-10 h-10 rounded-xl">
                                <div>
                                    <h3 class="text-sm font-black text-gray-900 leading-none">{{ $otherUser->name }}</h3>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">
                                        {{ $otherUser->role }} @if($otherUser->recruteur) — {{ $otherUser->recruteur->nom_entreprise }} @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.users') }}?search={{ $otherUser->email }}" class="p-2 text-gray-400 hover:text-indigo-600 transition" title="Voir profil">
                                    <i class="fas fa-user-circle text-lg"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Messages Area --}}
                        <div class="flex-1 overflow-y-auto p-10 space-y-6 custom-scrollbar flex flex-col">
                            @foreach($messages as $msg)
                                <div class="flex {{ $msg->expediteur_id == Auth::id() ? 'justify-end' : 'justify-start' }} animate__animated animate__fadeInUp animate__faster">
                                    <div class="max-w-[70%] {{ $msg->expediteur_id == Auth::id() ? 'order-1' : 'order-2' }}">
                                        <div class="p-5 rounded-[1.5rem] {{ $msg->expediteur_id == Auth::id() ? 'bg-indigo-600 text-white rounded-tr-none shadow-xl shadow-indigo-100' : 'bg-white text-gray-700 rounded-tl-none shadow-sm border border-gray-100' }}">
                                            <p class="text-sm font-medium leading-relaxed">{{ $msg->contenu }}</p>
                                        </div>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase mt-2 {{ $msg->expediteur_id == Auth::id() ? 'text-right' : 'text-left' }}">
                                            {{ $msg->created_at->translatedFormat('H:i') }}
                                            @if($msg->expediteur_id == Auth::id())
                                                <i class="fas fa-check-double ml-1 text-indigo-400"></i>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Input Area --}}
                        <div class="p-8 bg-white border-t border-gray-100 shrink-0">
                            <form action="{{ route('admin.messages.send') }}" method="POST" class="flex gap-4">
                                @csrf
                                <input type="hidden" name="destinataire_id" value="{{ $interlocuteur_id }}">
                                <div class="flex-1 relative">
                                    <input type="text" name="contenu" placeholder="Écrire un message..." required
                                           class="w-full py-4 px-8 bg-gray-50 border-none rounded-[2rem] focus:ring-4 focus:ring-indigo-100 font-medium text-gray-700 shadow-inner">
                                </div>
                                <button type="submit" class="bg-indigo-600 text-white w-14 h-14 rounded-full shadow-xl shadow-indigo-100 flex items-center justify-center hover:bg-indigo-700 hover:scale-110 transition-all active:scale-95">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach

                {{-- Empty State --}}
                <div id="no-conversation" class="h-full flex flex-col items-center justify-center text-gray-400 space-y-4 opacity-30">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center text-4xl">
                        <i class="fas fa-comments"></i>
                    </div>
                    <p class="font-black uppercase tracking-widest text-sm">Sélectionnez une discussion</p>
                </div>
            </main>
        </div>
    </div>
</div>

{{-- New Conversation Modal --}}
<div id="newMessageModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md hidden items-center justify-center z-50 animate__animated animate__fadeIn">
    <div class="bg-white rounded-[3rem] p-10 max-w-2xl w-full mx-4 shadow-2xl relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-50 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-center mb-10">
                <h3 class="text-3xl font-black text-gray-900 tracking-tight">Nouveau <span class="text-indigo-600">Contact</span></h3>
                <button onclick="document.getElementById('newMessageModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 transition text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.messages.send') }}" method="POST" class="space-y-8">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Destinataire (Recruteurs & Candidats)</label>
                    <div class="relative">
                        <select name="destinataire_id" class="w-full bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-100 py-4 px-6 font-bold text-gray-700 appearance-none shadow-sm" required>
                            <option value="">Sélectionner un contact...</option>
                            <optgroup label="Recruteurs Partenaires">
                                @foreach($recruteurs as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} — {{ $user->recruteur->nom_entreprise ?? 'Entreprise' }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Candidats">
                                @foreach($candidats as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} — {{ $user->candidat->titre_professionnel ?? 'Talent' }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Premier message</label>
                    <textarea name="contenu" rows="4" class="w-full bg-gray-50 border-none rounded-[2rem] focus:ring-4 focus:ring-indigo-100 p-8 font-medium text-gray-700 shadow-sm" placeholder="Rédigez votre message..." required></textarea>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="document.getElementById('newMessageModal').classList.add('hidden')" 
                            class="flex-1 py-5 text-sm font-black text-gray-500 uppercase tracking-widest">Annuler</button>
                    <button type="submit" 
                            class="flex-[2] py-5 text-sm font-black text-white bg-indigo-600 rounded-2xl hover:bg-indigo-700 shadow-2xl shadow-indigo-200 transition-all uppercase tracking-widest flex items-center justify-center gap-3">
                        Lancer la discussion <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showConversation(id) {
        // Hide all conversations
        document.querySelectorAll('.conversation-content').forEach(c => c.classList.add('hidden'));
        document.getElementById('no-conversation').classList.add('hidden');
        
        // Show selected
        const conv = document.getElementById('conv-' + id);
        if (conv) {
            conv.classList.remove('hidden');
            // Scroll to bottom
            const msgArea = conv.querySelector('.overflow-y-auto');
            msgArea.scrollTop = msgArea.scrollHeight;
        }

        // Update sidebar buttons
        document.querySelectorAll('.conversation-btn').forEach(btn => {
            if (btn.dataset.userId == id) {
                btn.classList.add('bg-indigo-50', 'border-l-4', 'border-indigo-600');
            } else {
                btn.classList.remove('bg-indigo-50', 'border-l-4', 'border-indigo-600');
            }
        });
    }

    // Auto-select if a conversation ID is in the URL hash (optional)
    window.onload = () => {
        const hash = window.location.hash.substring(1);
        if (hash) showConversation(hash);
    };
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    
    /* Animation for messages */
    .animate__fadeInUp {
        animation-duration: 0.4s;
    }
</style>
@endsection
