<div class="w-72 bg-[#0f172a] text-slate-300 flex-shrink-0 hidden md:flex flex-col h-screen sticky top-0 z-30 shadow-[4px_0_24px_rgba(0,0,0,0.1)] border-r border-slate-800/50">
    {{-- Brand Section --}}
    <div class="p-8">
        <div class="flex items-center gap-4 group cursor-pointer">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/20 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-rocket text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-black text-white tracking-tight leading-none">RecruitPro</h2>
                <p class="text-xs font-bold text-indigo-400 uppercase tracking-[0.2em] mt-1">Admin Panel</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto py-4 custom-scrollbar">
        {{-- Group 1: General --}}
        <div class="px-4 pb-3">
            <p class="text-xs font-black text-slate-500 uppercase tracking-[0.2em]">Menu Principal</p>
        </div>

        <a href="{{ route('admin.dashboard') }}" 
           class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 relative {{ $active == 'dashboard' ? 'bg-indigo-600/10 text-indigo-400 font-bold' : 'hover:bg-slate-800/50 hover:text-white' }}">
            @if($active == 'dashboard')
                <div class="absolute left-0 w-1 h-6 bg-indigo-500 rounded-r-full"></div>
            @endif
            <i class="fas fa-grid-2 text-lg mr-4 w-6 text-center {{ $active == 'dashboard' ? 'text-indigo-500' : 'text-slate-500 group-hover:text-indigo-400' }}"></i>
            <span class="text-[15px] tracking-wide">Vue d'ensemble</span>
        </a>

        {{-- Group 2: Management --}}
        <div class="px-4 pt-8 pb-3">
            <p class="text-xs font-black text-slate-500 uppercase tracking-[0.2em]">Gestion Plateforme</p>
        </div>

        <a href="{{ route('admin.recruteurs.verify') }}" 
           class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 relative {{ $active == 'verifications' ? 'bg-indigo-600/10 text-indigo-400 font-bold' : 'hover:bg-slate-800/50 hover:text-white' }}">
            @if($active == 'verifications')
                <div class="absolute left-0 w-1 h-6 bg-indigo-500 rounded-r-full"></div>
            @endif
            <i class="fas fa-user-shield text-lg mr-4 w-6 text-center {{ $active == 'verifications' ? 'text-indigo-500' : 'text-slate-500 group-hover:text-indigo-400' }}"></i>
            <span class="text-[15px] tracking-wide">Vérifications</span>
        </a>

        <a href="{{ route('admin.users') }}" 
           class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 relative {{ $active == 'users' ? 'bg-indigo-600/10 text-indigo-400 font-bold' : 'hover:bg-slate-800/50 hover:text-white' }}">
            @if($active == 'users')
                <div class="absolute left-0 w-1 h-6 bg-indigo-500 rounded-r-full"></div>
            @endif
            <i class="fas fa-users-gear text-lg mr-4 w-6 text-center {{ $active == 'users' ? 'text-indigo-500' : 'text-slate-500 group-hover:text-indigo-400' }}"></i>
            <span class="text-[15px] tracking-wide">Utilisateurs</span>
        </a>

        <a href="{{ route('admin.messages') }}" 
           class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 relative {{ $active == 'messages' ? 'bg-indigo-600/10 text-indigo-400 font-bold' : 'hover:bg-slate-800/50 hover:text-white' }}">
            @if($active == 'messages')
                <div class="absolute left-0 w-1 h-6 bg-indigo-500 rounded-r-full"></div>
            @endif
            <i class="fas fa-comment-dots text-lg mr-4 w-6 text-center {{ $active == 'messages' ? 'text-indigo-500' : 'text-slate-500 group-hover:text-indigo-400' }}"></i>
            <span class="text-[15px] tracking-wide">Messagerie</span>
        </a>

        <a href="{{ route('admin.notifications') }}" 
           class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 relative {{ $active == 'notifications' ? 'bg-indigo-600/10 text-indigo-400 font-bold' : 'hover:bg-slate-800/50 hover:text-white' }}">
            @if($active == 'notifications')
                <div class="absolute left-0 w-1 h-6 bg-indigo-500 rounded-r-full"></div>
            @endif
            <i class="fas fa-bell text-lg mr-4 w-6 text-center {{ $active == 'notifications' ? 'text-indigo-500' : 'text-slate-500 group-hover:text-indigo-400' }}"></i>
            <span class="text-[15px] tracking-wide">Notifications</span>
        </a>

        {{-- Group 3: Monitoring --}}
        <div class="px-4 pt-8 pb-3">
            <p class="text-xs font-black text-slate-500 uppercase tracking-[0.2em]">Surveillance</p>
        </div>

        <a href="{{ route('admin.logs') }}" 
           class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 relative {{ $active == 'logs' ? 'bg-indigo-600/10 text-indigo-400 font-bold' : 'hover:bg-slate-800/50 hover:text-white' }}">
            @if($active == 'logs')
                <div class="absolute left-0 w-1 h-6 bg-indigo-500 rounded-r-full"></div>
            @endif
            <i class="fas fa-fingerprint text-lg mr-4 w-6 text-center {{ $active == 'logs' ? 'text-indigo-500' : 'text-slate-500 group-hover:text-indigo-400' }}"></i>
            <span class="text-[15px] tracking-wide">Audit Logs</span>
        </a>

        <a href="{{ route('admin.statistics') }}" 
           class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 relative {{ $active == 'statistics' ? 'bg-indigo-600/10 text-indigo-400 font-bold' : 'hover:bg-slate-800/50 hover:text-white' }}">
            @if($active == 'statistics')
                <div class="absolute left-0 w-1 h-6 bg-indigo-500 rounded-r-full"></div>
            @endif
            <i class="fas fa-analytics text-lg mr-4 w-6 text-center {{ $active == 'statistics' ? 'text-indigo-500' : 'text-slate-500 group-hover:text-indigo-400' }}"></i>
            <span class="text-[15px] tracking-wide">Analyses</span>
        </a>
    </nav>

    {{-- User Profile Footer --}}
    <div class="p-6 border-t border-slate-800/50 bg-[#0f172a]">
        <div class="bg-slate-800/30 rounded-[1.5rem] p-4 border border-slate-700/30">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=6366f1&color=fff" 
                         class="w-10 h-10 rounded-xl shadow-lg border-2 border-slate-700">
                    <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-500 border-2 border-[#0f172a] rounded-full"></span>
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-sm font-black text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest mt-0.5">Administrateur</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-slate-700/50 text-slate-300 text-sm font-black hover:bg-rose-500 hover:text-white transition-all duration-300">
                    <i class="fas fa-power-off"></i>
                    Quitter la session
                </button>
            </form>
        </div>
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
        background: #1e293b;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #334155;
    }
</style>
