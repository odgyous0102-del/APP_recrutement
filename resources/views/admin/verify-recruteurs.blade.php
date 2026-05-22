@extends('layouts.app')

@section('title', 'Vérification des Recruteurs - Admin')

@section('content')
<div class="flex h-screen bg-[#f8fafc] overflow-hidden">
    @include('admin.partials.sidebar', ['active' => 'verifications'])

    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Barre supérieure --}}
        @include('admin.partials.header', ['title' => 'Vérification Partenaires'])

        <main class="flex-1 overflow-y-auto p-10 space-y-12 animate__animated animate__fadeIn">
            {{-- Recruteurs en attente --}}
            <section>
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3">
                        <i class="fas fa-hourglass-half text-amber-500"></i>
                        Dossiers en cours d'examen
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 gap-6">
                    @forelse($recruteursEnAttente as $recruteur)
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                            <div class="flex items-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center text-3xl text-gray-300 group-hover:bg-amber-50 group-hover:text-amber-500 transition-colors shadow-inner">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="ml-6">
                                    <h3 class="text-xl font-black text-gray-900 tracking-tight">{{ $recruteur->nom_entreprise }}</h3>
                                    <div class="flex items-center gap-4 mt-1">
                                        <p class="text-sm font-bold text-gray-500">{{ $recruteur->user->name }}</p>
                                        <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
                                        <p class="text-xs text-indigo-500 font-bold uppercase tracking-tighter">{{ $recruteur->user->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                <button onclick="openMessageModal({{ $recruteur->user->id }}, '{{ addslashes($recruteur->nom_entreprise) }}')" 
                                        class="px-6 py-3 bg-indigo-50 text-indigo-600 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all shadow-sm flex items-center gap-2">
                                    <i class="fas fa-comment-alt"></i> Contacter
                                </button>
                                
                                <form action="{{ route('admin.recruteurs.approve', $recruteur->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="px-8 py-3 bg-green-500 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-green-600 transition-all shadow-xl shadow-green-100 flex items-center gap-2">
                                        <i class="fas fa-check"></i> Approuver
                                    </button>
                                </form>

                                <button onclick="openRejectModal({{ $recruteur->id }})" class="px-6 py-3 bg-red-50 text-red-600 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all flex items-center gap-2">
                                    <i class="fas fa-times"></i> Rejeter
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-gray-50/50 border-2 border-dashed border-gray-200 rounded-[3rem] p-20 text-center">
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                            <i class="fas fa-check-double text-4xl text-green-500"></i>
                        </div>
                        <p class="text-lg font-black text-gray-400 tracking-tight">Tous les dossiers ont été traités !</p>
                        <p class="text-sm text-gray-400 font-medium mt-2">Revenez plus tard pour de nouveaux recrutements.</p>
                    </div>
                    @endforelse
                </div>
            </section>

            {{-- Historique des décisions --}}
            <section>
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3">
                        <i class="fas fa-history text-indigo-500"></i>
                        Historique récent
                    </h2>
                </div>
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50/50 text-gray-400 text-[10px] uppercase font-black tracking-widest">
                            <tr>
                                <th class="px-8 py-6">Entreprise</th>
                                <th class="px-8 py-6">Résultat du dossier</th>
                                <th class="px-8 py-6">Date de traitement</th>
                                <th class="px-8 py-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($historiqueVerifications as $audit)
                            <tr class="hover:bg-gray-50 transition group">
                                <td class="px-8 py-6">
                                    <div class="font-black text-gray-900 tracking-tight">{{ $audit->recruteur->nom_entreprise ?? 'Entreprise supprimée' }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold">{{ $audit->recruteur->user->email ?? '' }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    @if($audit->statut === 'approuve')
                                        <span class="bg-green-50 text-green-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-green-100 flex items-center w-max gap-2">
                                            <i class="fas fa-check-circle text-[8px]"></i> Approuvé
                                        </span>
                                    @else
                                        <span class="bg-red-50 text-red-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-red-100 flex items-center w-max gap-2">
                                            <i class="fas fa-times-circle text-[8px]"></i> Rejeté
                                        </span>
                                    @endif
                                    @if($audit->commentaire)
                                        <p class="text-[10px] text-gray-400 mt-2 italic font-medium">"{{ Str::limit($audit->commentaire, 50) }}"</p>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-sm font-bold text-gray-600">{{ \Carbon\Carbon::parse($audit->date_decision)->translatedFormat('d M Y') }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase">Par {{ $audit->admin->user->name ?? 'Admin' }}</div>
                                </td>
                                <td class="px-8 py-6 text-right flex justify-end gap-2">
                                    @if($audit->recruteur && $audit->recruteur->user)
                                    <form action="{{ route('admin.users.toggle', $audit->recruteur->user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                title="{{ $audit->recruteur->user->is_active ? 'Désactiver le compte' : 'Activer le compte' }}"
                                                class="p-2.5 rounded-xl transition-all shadow-sm opacity-0 group-hover:opacity-100 {{ $audit->recruteur->user->is_active ? 'bg-red-50 text-red-400 hover:bg-red-600 hover:text-white' : 'bg-green-50 text-green-400 hover:bg-green-600 hover:text-white' }}">
                                            <i class="fas {{ $audit->recruteur->user->is_active ? 'fa-user-slash' : 'fa-user-check' }} text-sm"></i>
                                        </button>
                                    </form>

                                    <button onclick="openMessageModal({{ $audit->recruteur->user->id }}, '{{ addslashes($audit->recruteur->nom_entreprise) }}')" 
                                            title="Contacter"
                                            class="p-2.5 rounded-xl bg-gray-50 text-gray-400 hover:bg-indigo-600 hover:text-white transition-all shadow-sm opacity-0 group-hover:opacity-100">
                                        <i class="fas fa-envelope text-sm"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</div>

{{-- Modal de Message --}}
<div id="quickMessageModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md hidden items-center justify-center z-50 animate__animated animate__fadeIn">
    <div class="bg-white rounded-[3rem] p-10 max-w-lg w-full mx-4 shadow-2xl relative overflow-hidden">
        <div class="relative z-10">
            <h3 class="text-2xl font-black text-gray-900 mb-6">Contacter <span id="target-name" class="text-indigo-600"></span></h3>
            <form action="{{ route('admin.messages.send') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="destinataire_id" id="target-id">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Message administratif</label>
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

{{-- Reject Modal Premium --}}
<div id="rejectModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md hidden items-center justify-center z-50 animate__animated animate__fadeIn">
    <div class="bg-white rounded-[3rem] p-10 max-w-md w-full mx-4 shadow-2xl relative overflow-hidden">
        <div class="relative z-10 text-center">
            <div class="w-20 h-20 bg-red-50 text-red-600 rounded-[1.5rem] flex items-center justify-center text-3xl mx-auto mb-6 shadow-inner">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-2">Refuser le dossier</h3>
            <p class="text-gray-500 text-sm font-medium mb-8">Veuillez indiquer le motif du rejet pour informer le recruteur.</p>
            
            <form id="rejectForm" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="action" value="reject">
                <div class="space-y-2 text-left">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Motif du rejet</label>
                    <textarea name="commentaire" rows="4" class="w-full bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-red-100 p-6 font-medium text-gray-700 shadow-sm" placeholder="Ex: Documents non conformes..."></textarea>
                </div>
                <div class="flex gap-4">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 py-4 font-black text-gray-500 uppercase text-xs">Annuler</button>
                    <button type="submit" class="flex-[2] py-4 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-red-100 hover:bg-red-700 transition-all active:scale-95">Confirmer le rejet</button>
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

    function openRejectModal(id) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = `/admin/recruteurs/${id}/approve`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection
