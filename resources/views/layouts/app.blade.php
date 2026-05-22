{{-- Main Application Layout --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="@yield('meta-description', 'Plateforme de recrutement - Connectez les meilleurs talents avec les opportunités idéales')">
    <meta name="keywords"
        content="@yield('meta-keywords', 'recrutement, emploi, candidat, recruteur, offre d\'emploi')">
    <meta name="author" content="RecruitPro">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og-title', 'RecruitPro - Votre plateforme de recrutement')">
    <meta property="og:description"
        content="@yield('og-description', 'Connectez les meilleurs talents avec les opportunités idéales')">
    <meta property="og:image" content="@yield('og-image', asset('images/og-image.jpg'))">

    {{-- Twitter --}}
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('twitter-title', 'RecruitPro - Votre plateforme de recrutement')">
    <meta property="twitter:description"
        content="@yield('twitter-description', 'Connectez les meilleurs talents avec les opportunités idéales')">
    <meta property="twitter:image" content="@yield('twitter-image', asset('images/og-image.jpg'))">

    <title>@yield('title', 'RecruitPro - Plateforme de Recrutement')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Icons --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- Page-specific CSS --}}
    @stack('styles')

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Tailwind Configuration --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        purple: {
                            50: '#faf5ff',
                            100: '#f3e8ff',
                            200: '#e9d5ff',
                            300: '#d8b4fe',
                            400: '#c084fc',
                            500: '#a855f7',
                            600: '#9333ea',
                            700: '#7c3aed',
                            800: '#6b21a8',
                            900: '#581c87',
                        },
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        bounceGentle: {
                            '0%, 100%': { transform: 'translateY(-5%)' },
                            '50%': { transform: 'translateY(0)' },
                        },
                    },
                },
            },
        }
    </script>
</head>

<body class="font-inter bg-gray-50 text-gray-900">
    {{-- Flash Messages Unifiés --}}
    @include('partials.flash-messages')

    {{-- Header --}}
    @include('partials.header')

    {{-- Main Content --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Scripts --}}
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- Page-specific JavaScript --}}
    @stack('scripts')

    {{-- Global JavaScript --}}
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add loading states for forms (skip AJAX forms)
        document.querySelectorAll('form:not([data-ajax])').forEach(form => {
            form.addEventListener('submit', function (e) {
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Chargement...';
                }
            });
        });

        // Initialize tooltips
        document.querySelectorAll('[title]').forEach(element => {
            element.classList.add('relative', 'group');

            const tooltip = document.createElement('div');
            tooltip.className = 'absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap';
            tooltip.textContent = element.getAttribute('title');

            element.appendChild(tooltip);
        });
    </script>
</body>

</html>