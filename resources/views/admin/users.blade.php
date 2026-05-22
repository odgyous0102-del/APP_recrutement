@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs - Admin')

@section('content')
<div class="flex h-screen bg-[#f8fafc] overflow-hidden">
    @include('admin.partials.sidebar', ['active' => 'users'])

    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Barre supérieure --}}
        @include('admin.partials.header', ['title' => 'Gestion Utilisateurs'])

        <main class="flex-1 overflow-y-auto p-10 space-y-10 animate__animated animate__fadeIn">
            {{-- Filtres Premium --}}
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                <form action="{{ route('admin.users') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Recherche</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email..." 
                                   class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-100 font-bold text-gray-700 shadow-sm">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Rôle</label>
                        <select name="role" class="w-full py-3.5 px-6 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-100 font-bold text-gray-700 shadow-sm">
                            <option value="">Tous les rôles</option>
                            <option value="candidat" {{ request('role') == 'candidat' ? 'selected' : '' }}>Candidat</option>
                            <option value="recruteur" {{ request('role') == 'recruteur' ? 'selected' : '' }}>Recruteur</option>
                            <option value="administrateur" {{ request('role') == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Statut</label>
                        <select name="status" class="w-full py-3.5 px-6 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-100 font-bold text-gray-700 shadow-sm">
                            <option value="all">Tous les statuts</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">
                            Appliquer les filtres
                        </button>
                    </div>
                </form>
            </div>

            {{-- Liste des utilisateurs Premium --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50 text-gray-400 text-[10px] uppercase font-black tracking-widest">
                        <tr>
                            <th class="px-8 py-6">Utilisateur</th>
                            <th class="px-8 py-6">Identité & Rôle</th>
                            <th class="px-8 py-6">Statut Compte</th>
                            <th class="px-8 py-6">Date d'inscription</th>
                            <th class="px-8 py-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($users as $user)
                        <tr class="hover:bg-indigo-50/20 transition group">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="relative group-hover:scale-110 transition-transform duration-300">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" class="w-12 h-12 rounded-2xl mr-4 shadow-sm">
                                        <span class="absolute -bottom-1 -right-1 w-4 h-4 {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }} border-2 border-white rounded-full"></span>
                                    </div>
                                    <div>
                                        <div class="font-black text-gray-900 text-sm tracking-tight">{{ $user->name }}</div>
                                        <div class="text-[10px] text-gray-400 font-bold">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg {{ $user->role == 'administrateur' ? 'bg-purple-50 text-purple-600' : ($user->role == 'recruteur' ? 'bg-indigo-50 text-indigo-600' : 'bg-blue-50 text-blue-600') }}">
                                    <i class="fas fa-{{ $user->role == 'administrateur' ? 'shield-alt' : ($user->role == 'recruteur' ? 'building' : 'user') }} mr-1.5"></i>
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                @if($user->is_active)
                                    <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-green-100">
                                        Compte Actif
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-red-100">
                                        Compte Bloqué
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-bold text-gray-600">{{ $user->created_at->translatedFormat('d M Y') }}</div>
                                <div class="text-[10px] text-gray-400 font-bold">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @if($user->role == 'recruteur')
                                    <button onclick="openMessageModal({{ $user->id }}, '{{ addslashes($user->name) }}')" 
                                            class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm"
                                            title="Envoyer un message">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                    @endif
                                    
                                    @if($user->id !== Auth::id())
                                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="p-2.5 rounded-xl border {{ $user->is_active ? 'border-red-100 text-red-600 hover:bg-red-600 hover:text-white' : 'border-green-100 text-green-600 hover:bg-green-600 hover:text-white' }} transition-all shadow-sm"
                                                title="{{ $user->is_active ? 'Désactiver' : 'Activer' }}">
                                            <i class="fas fa-{{ $user->is_active ? 'user-slash' : 'user-check' }}"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-8">
                {{ $users->links() }}
            </div>
        </main>
    </div>
</div>

{{-- Message Modal Shortcut --}}
<div id="quickMessageModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md hidden items-center justify-center z-50 animate__animated animate__fadeIn">
    <div class="bg-white rounded-[3rem] p-10 max-w-lg w-full mx-4 shadow-2xl relative overflow-hidden">
        <div class="relative z-10">
            <h3 class="text-2xl font-black text-gray-900 mb-6">Contacter <span id="target-name" class="text-indigo-600"></span></h3>
            <form action="{{ route('admin.messages.send') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="destinataire_id" id="target-id">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Message</label>
                    <textarea name="contenu" rows="5" class="w-full bg-gray-50 border-none rounded-3xl focus:ring-4 focus:ring-indigo-100 p-6 font-medium text-gray-700 shadow-sm" placeholder="Rédigez votre message..." required></textarea>
                </div>
                <div class="flex gap-4">
                    <button type="button" onclick="document.getElementById('quickMessageModal').classList.add('hidden')" class="flex-1 py-4 font-black text-gray-500 uppercase text-xs">Annuler</button>
                    <button type="submit" class="flex-[2] py-4 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">Envoyer le message</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openMessageModal(id, name) {
        document.getElementById('target-id').value = id;
        document.getElementById('target-name').innerText = name;
        document.getElementById('quickMessageModal').classList.remove('hidden');
    }
</script>
@endsection
