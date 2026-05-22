@extends('layouts.app')

@section('title', 'Logs d\'Audit - Admin')

@section('content')
<div class="flex h-screen bg-[#f8fafc] overflow-hidden">
    @include('admin.partials.sidebar', ['active' => 'logs'])

    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Barre supérieure --}}
        @include('admin.partials.header', ['title' => 'Journaux d\'Audit'])

        <main class="flex-1 overflow-y-auto p-8">
            {{-- Filtres --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
                <form action="{{ route('admin.logs') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Recherche (Utilisateur, Action)</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ex: approve_recruteur..." class="w-full border-gray-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Action</label>
                        <select name="action" class="w-full border-gray-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Toutes les actions</option>
                            @foreach($actions as $act)
                                <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ $act }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-700 transition">
                            Filtrer
                        </button>
                    </div>
                </form>
            </div>

            {{-- Liste des logs --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-medium">
                            <tr>
                                <th class="px-6 py-4">Utilisateur</th>
                                <th class="px-6 py-4">Action</th>
                                <th class="px-6 py-4">Détails</th>
                                <th class="px-6 py-4">IP</th>
                                <th class="px-6 py-4">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $log->user->name ?? 'Système' }}</div>
                                    <div class="text-xs text-gray-500">{{ $log->user->email ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-[10px] font-bold uppercase rounded bg-gray-100 text-gray-700">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-gray-600 max-w-xs truncate" title="{{ json_encode($log->nouvelle_val) }}">
                                        {{ $log->model_type }} #{{ $log->model_id }}
                                        @if($log->nouvelle_val)
                                            <br><span class="text-gray-400">{{ json_encode($log->nouvelle_val) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Aucune entrée trouvée</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        </main>
    </div>
</div>
@endsection
