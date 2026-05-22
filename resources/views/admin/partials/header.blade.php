<header
    class="bg-white/80 backdrop-blur-md border-b border-gray-100 h-20 flex items-center justify-between px-10 sticky top-0 z-10">
    <div class="flex items-center">
        <div class="w-1.5 h-8 bg-indigo-600 rounded-full mr-4"></div>
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ $title }}</h1>
    </div>
    <div class="flex items-center space-x-6">
        {!! $extraAction ?? '' !!}
        {{-- Cloche de Notification Dynamique --}}
        <div class="relative group">
            <button
                class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all relative">
                <i class="fas fa-bell"></i>
                @if(count($unreadNotifications ?? []) > 0)
                    <span
                        class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full border-2 border-white animate-pulse"></span>
                @endif
            </button>

            {{-- Dropdown Admin Notifications --}}
            <div
                class="absolute right-0 mt-3 w-80 bg-white rounded-3xl shadow-2xl border border-slate-100 py-4 opacity-0 invisible translate-y-2 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 z-[101]">
                <div class="px-5 pb-3 border-b border-slate-50 flex justify-between items-center">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest">Notifications</h4>
                    @if(count($unreadNotifications ?? []) > 0)
                        <form action="{{ route('notifications.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="text-[10px] font-bold text-indigo-500 hover:text-indigo-700 transition-colors">Tout
                                lire</button>
                        </form>
                    @endif
                </div>

                <div class="max-h-80 overflow-y-auto custom-scrollbar">
                    @forelse($unreadNotifications ?? [] as $notification)
                        <div
                            class="px-5 py-4 hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0 relative group/item">
                            <div class="flex gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-indigo-50 flex-shrink-0 flex items-center justify-center text-indigo-500">
                                    @if($notification->type == 'nouvelle_candidature') <i
                                        class="fas fa-file-alt text-xs"></i>
                                    @elseif($notification->type == 'entretien_planifie') <i
                                        class="fas fa-calendar-check text-xs"></i>
                                    @elseif($notification->type == 'system_broadcast') <i
                                        class="fas fa-bullhorn text-xs"></i>
                                    @else <i class="fas fa-bell text-xs"></i>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[13px] font-bold text-slate-800 leading-snug">{{ $notification->titre }}
                                    </p>
                                    <p class="text-[11px] text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                        {{ $notification->message }}</p>
                                    <p class="text-[10px] text-slate-400 mt-2 font-medium">
                                        {{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <form action="{{ route('notifications.markRead', $notification) }}" method="POST"
                                class="absolute top-4 right-4 opacity-0 group-hover/item:opacity-100 transition-opacity">
                                @csrf
                                <button type="submit" title="Marquer comme lu"
                                    class="w-6 h-6 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm">
                                    <i class="fas fa-check text-[8px]"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="px-5 py-10 text-center">
                            <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-bell-slash text-slate-200 text-lg"></i>
                            </div>
                            <p class="text-xs font-bold text-slate-400 italic">Aucune nouvelle notification</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Profil Admin Shortcut --}}
        <div class="flex items-center bg-gray-50 rounded-2xl p-1.5 pl-4 border border-gray-100">
            <div class="text-right mr-3 hidden sm:block">
                <p class="text-xs font-black text-gray-900 uppercase tracking-widest">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-indigo-500 font-bold uppercase">Super Admin</p>
            </div>
            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=6366f1&color=fff"
                class="w-10 h-10 rounded-xl shadow-sm border-2 border-white">
        </div>
    </div>
</header>