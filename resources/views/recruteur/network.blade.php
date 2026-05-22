@extends('layouts.app')

@section('title', 'Réseau des Recruteurs - RecruitPro')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Réseau des Recruteurs</h1>
                <p class="mt-2 text-gray-500">Communiquez avec vos pairs et échangez sur les opportunités.</p>
            </div>
            <a href="{{ route('recruteur.dashboard') }}" class="flex items-center text-indigo-600 hover:text-indigo-800 font-bold transition">
                <i class="fas fa-arrow-left mr-2"></i> Retour au Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Liste des Recruteurs --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50">
                        <h2 class="font-bold text-gray-800">Recruteurs Disponibles</h2>
                    </div>
                    <div class="p-4 space-y-4 max-h-[600px] overflow-y-auto">
                        @foreach($autres_recruteurs as $recruteur)
                        <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-2xl transition cursor-pointer" onclick="selectRecruteur({{ $recruteur->user->id }}, '{{ $recruteur->user->name }}')">
                            <div class="flex items-center">
                                <div class="relative">
                                    <img src="{{ $recruteur->logo_entreprise ? asset('storage/' . $recruteur->logo_entreprise) : 'https://ui-avatars.com/api/?name=' . $recruteur->user->name }}" class="w-10 h-10 rounded-xl object-cover border">
                                    <span class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-bold text-gray-900">{{ $recruteur->user->name }}</p>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-tighter">{{ $recruteur->nom_entreprise }}</p>
                                </div>
                            </div>
                            <i class="fas fa-comment-dots text-indigo-400"></i>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Messagerie --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 h-[700px] flex flex-col">
                    {{-- Header Conversation --}}
                    <div id="chatHeader" class="p-6 border-b border-gray-50 flex items-center justify-between">
                        <div class="flex items-center">
                            <div id="chatAvatar" class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 font-bold mr-4">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div>
                                <h3 id="chatName" class="font-bold text-gray-900 text-lg">Sélectionnez une conversation</h3>
                                <p id="chatStatus" class="text-xs text-gray-400">Cliquez sur un recruteur pour commencer</p>
                            </div>
                        </div>
                    </div>

                    {{-- Zone de messages --}}
                    <div id="chatBody" class="flex-1 p-6 overflow-y-auto bg-gray-50/30 space-y-4">
                        <div class="h-full flex flex-col items-center justify-center text-gray-400 italic">
                            <i class="fas fa-paper-plane text-5xl mb-4 opacity-20"></i>
                            <p>Vos messages s'afficheront ici</p>
                        </div>
                    </div>

                    {{-- Input Message --}}
                    <div class="p-6 border-t border-gray-50">
                        <form id="messageForm" class="flex space-x-4">
                            @csrf
                            <input type="hidden" name="destinataire_id" id="destinataire_id">
                            <input type="text" name="contenu" id="messageInput" placeholder="Écrivez votre message..." class="flex-1 border-gray-200 rounded-2xl focus:border-indigo-500 focus:ring-indigo-500 px-6" disabled>
                            <button type="submit" id="sendBtn" class="bg-indigo-600 text-white w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition disabled:opacity-50" disabled>
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const conversations = @json($conversations);
    const authId = {{ Auth::id() }};

    function selectRecruteur(id, name) {
        document.getElementById('destinataire_id').value = id;
        document.getElementById('chatName').innerText = name;
        document.getElementById('chatStatus').innerText = 'En ligne';
        document.getElementById('messageInput').disabled = false;
        document.getElementById('sendBtn').disabled = false;
        
        loadMessages(id);
    }

    function loadMessages(interlocuteurId) {
        const chatBody = document.getElementById('chatBody');
        chatBody.innerHTML = '';
        
        const messages = conversations[interlocuteurId] || [];
        
        if (messages.length === 0) {
            chatBody.innerHTML = '<div class="h-full flex flex-col items-center justify-center text-gray-400 italic"><p>Aucun message. Envoyez le premier !</p></div>';
            return;
        }

        messages.forEach(msg => {
            const isMe = msg.expediteur_id == authId;
            const msgDiv = document.createElement('div');
            msgDiv.className = `flex ${isMe ? 'justify-end' : 'justify-start'}`;
            
            msgDiv.innerHTML = `
                <div class="max-w-[70%] ${isMe ? 'bg-indigo-600 text-white rounded-l-2xl rounded-tr-2xl' : 'bg-white text-gray-800 rounded-r-2xl rounded-tl-2xl shadow-sm'} p-4">
                    <p class="text-sm">${msg.contenu}</p>
                    <p class="text-[10px] mt-1 ${isMe ? 'text-indigo-200' : 'text-gray-400'}">${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                </div>
            `;
            chatBody.appendChild(msgDiv);
        });
        
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    document.getElementById('messageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('messageInput');
        const content = input.value;
        const destId = document.getElementById('destinataire_id').value;

        if (!content || !destId) return;

        fetch('{{ route("recruteur.messages.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                destinataire_id: destId,
                contenu: content
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                input.value = '';
                // Rafraîchir localement ou via AJAX (ici simplification par ajout direct)
                const chatBody = document.getElementById('chatBody');
                if (chatBody.querySelector('.italic')) chatBody.innerHTML = '';
                
                const msgDiv = document.createElement('div');
                msgDiv.className = 'flex justify-end';
                msgDiv.innerHTML = `
                    <div class="max-w-[70%] bg-indigo-600 text-white rounded-l-2xl rounded-tr-2xl p-4">
                        <p class="text-sm">${content}</p>
                        <p class="text-[10px] mt-1 text-indigo-200">À l'instant</p>
                    </div>
                `;
                chatBody.appendChild(msgDiv);
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        });
    });
</script>
@endsection
