{{-- Messages Flash Unifiés --}}
@if(session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info'))
    <div id="flashMessages" class="fixed top-24 right-4 z-[999999999] space-y-3 max-w-sm w-full pointer-events-none">

        @if(session('success'))
            <div class="flash-message success pointer-events-auto flex items-start gap-3 bg-white text-gray-800 px-5 py-4 rounded-2xl shadow-2xl border-l-4 border-green-500"
                 style="opacity:0; transform: translateX(120%); transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-green-700">Succès !</p>
                    <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">{{ session('success') }}</p>
                </div>
                <button onclick="removeFlashMessage(this)" class="text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0 ml-1">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="flash-message error pointer-events-auto flex items-start gap-3 bg-white text-gray-800 px-5 py-4 rounded-2xl shadow-2xl border-l-4 border-red-500"
                 style="opacity:0; transform: translateX(120%); transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-red-700">Erreur</p>
                    <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">{{ session('error') }}</p>
                </div>
                <button onclick="removeFlashMessage(this)" class="text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0 ml-1">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div class="flash-message warning pointer-events-auto flex items-start gap-3 bg-white text-gray-800 px-5 py-4 rounded-2xl shadow-2xl border-l-4 border-amber-500"
                 style="opacity:0; transform: translateX(120%); transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-amber-600 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-amber-700">Attention</p>
                    <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">{{ session('warning') }}</p>
                </div>
                <button onclick="removeFlashMessage(this)" class="text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0 ml-1">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        @endif

        @if(session('info'))
            <div class="flash-message info pointer-events-auto flex items-start gap-3 bg-white text-gray-800 px-5 py-4 rounded-2xl shadow-2xl border-l-4 border-blue-500"
                 style="opacity:0; transform: translateX(120%); transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-blue-700">Information</p>
                    <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">{{ session('info') }}</p>
                </div>
                <button onclick="removeFlashMessage(this)" class="text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0 ml-1">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        @endif
    </div>

    <script>
        // Déclencher l'animation d'entrée immédiatement
        (function() {
            function showMessages() {
                const messages = document.querySelectorAll('.flash-message');
                messages.forEach((message, index) => {
                    setTimeout(() => {
                        message.style.opacity = '1';
                        message.style.transform = 'translateX(0)';
                    }, index * 150);

                    // Durée d'affichage selon le type
                    const isError   = message.classList.contains('error');
                    const isWarning = message.classList.contains('warning');
                    const delay = isError ? 8000 : isWarning ? 6000 : 5000;

                    setTimeout(() => {
                        const btn = message.querySelector('button');
                        if (btn) removeFlashMessage(btn);
                    }, delay + (index * 150));
                });
            }

            // Lancer dès que le DOM est prêt
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', showMessages);
            } else {
                showMessages();
            }
        })();

        function removeFlashMessage(button) {
            const message = button.closest('.flash-message');
            if (!message) return;
            message.style.opacity = '0';
            message.style.transform = 'translateX(120%)';
            setTimeout(() => {
                message.remove();
                const container = document.getElementById('flashMessages');
                if (container && container.querySelectorAll('.flash-message').length === 0) {
                    container.remove();
                }
            }, 400);
        }
    </script>
@endif
