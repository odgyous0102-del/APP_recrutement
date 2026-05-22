@extends('layouts.app')

@section('title', 'RecruitPro - Plateforme de Recrutement')

@section('meta-description', 'Connectez les meilleurs talents avec les opportunités d\'emploi idéales sur RecruitPro')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-20">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    Trouvez l'emploi de vos rêves
                </h1>
                <p class="text-xl mb-8 text-purple-100">
                    Rejoignez des milliers de candidats et recruteurs qui font confiance à RecruitPro pour leurs besoins en recrutement.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('register.choice') }}" class="btn btn-lg bg-white text-purple-600 hover:bg-purple-50">
                        <i class="fas fa-user-plus mr-2"></i>
                        Créer un compte
                    </a>
                    <a href="#" class="btn btn-lg border-2 border-white text-white hover:bg-white hover:text-purple-600">
                        <i class="fas fa-search mr-2"></i>
                        Explorer les offres
                    </a>
                </div>
            </div>
            <div class="md:w-1/2 md:pl-12">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-8">
                    <h3 class="text-2xl font-semibold mb-4">Chiffres clés</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold">10,000+</div>
                            <div class="text-purple-200">Candidats</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold">500+</div>
                            <div class="text-purple-200">Entreprises</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold">2,000+</div>
                            <div class="text-purple-200">Offres actives</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold">85%</div>
                            <div class="text-purple-200">Taux de succès</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Pourquoi choisir RecruitPro ?
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Une plateforme complète conçue pour simplifier le processus de recrutement pour tous
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4">Recherche avancée</h3>
                <p class="text-gray-600">
                    Trouvez les offres parfaites grâce à notre système de recherche intelligent et aux filtres personnalisés.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-users text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4">Réseau qualifié</h3>
                <p class="text-gray-600">
                    Accédez à un réseau de talents vérifiés et d'entreprises de confiance dans tous les secteurs.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4">Suivi en temps réel</h3>
                <p class="text-gray-600">
                    Suivez l'évolution de vos candidatures et recrutements avec des tableaux de bord détaillés.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- How it Works Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Comment ça fonctionne ?
            </h2>
            <p class="text-xl text-gray-600">
                Trois étapes simples pour trouver ou offrir l'emploi parfait
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="relative">
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="w-12 h-12 bg-purple-600 text-white rounded-full flex items-center justify-center mx-auto mb-6 text-xl font-bold">
                        1
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Inscrivez-vous</h3>
                    <p class="text-gray-600">
                        Créez votre profil gratuit en quelques minutes et commencez à explorer les opportunités.
                    </p>
                </div>
                <div class="hidden md:block absolute top-1/2 -right-4 transform -translate-y-1/2">
                    <i class="fas fa-arrow-right text-purple-600 text-2xl"></i>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="relative">
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="w-12 h-12 bg-purple-600 text-white rounded-full flex items-center justify-center mx-auto mb-6 text-xl font-bold">
                        2
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Connectez-vous</h3>
                    <p class="text-gray-600">
                        Postulez aux offres ou publiez vos annonces et commencez à interagir avec les recruteurs.
                    </p>
                </div>
                <div class="hidden md:block absolute top-1/2 -right-4 transform -translate-y-1/2">
                    <i class="fas fa-arrow-right text-purple-600 text-2xl"></i>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="relative">
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="w-12 h-12 bg-purple-600 text-white rounded-full flex items-center justify-center mx-auto mb-6 text-xl font-bold">
                        3
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Réussissez</h3>
                    <p class="text-gray-600">
                        Trouvez le candidat ou l'emploi parfait et faites évoluer votre carrière ou votre entreprise.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Témoignages
            </h2>
            <p class="text-xl text-gray-600">
                Ce que nos utilisateurs disent de RecruitPro
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="bg-gray-50 rounded-lg p-8">
                <div class="flex items-center mb-4">
                    <img src="https://picsum.photos/seed/user1/50/50.jpg" alt="Marie Dupont" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-semibold">Marie Dupont</h4>
                        <p class="text-gray-600 text-sm">Développeuse Web</p>
                    </div>
                </div>
                <div class="flex mb-4">
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                </div>
                <p class="text-gray-700">
                    "Grâce à RecruitPro, j'ai trouvé mon emploi de rêve en moins d'un mois. Le processus était simple et efficace."
                </p>
            </div>

            <!-- Testimonial 2 -->
            <div class="bg-gray-50 rounded-lg p-8">
                <div class="flex items-center mb-4">
                    <img src="https://picsum.photos/seed/user2/50/50.jpg" alt="Jean Martin" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-semibold">Jean Martin</h4>
                        <p class="text-gray-600 text-sm">RH Manager</p>
                    </div>
                </div>
                <div class="flex mb-4">
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                </div>
                <p class="text-gray-700">
                    "RecruitPro a transformé notre processus de recrutement. Nous trouvons des candidats qualifiés rapidement."
                </p>
            </div>

            <!-- Testimonial 3 -->
            <div class="bg-gray-50 rounded-lg p-8">
                <div class="flex items-center mb-4">
                    <img src="https://picsum.photos/seed/user3/50/50.jpg" alt="Sophie Bernard" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-semibold">Sophie Bernard</h4>
                        <p class="text-gray-600 text-sm">Designer UX</p>
                    </div>
                </div>
                <div class="flex mb-4">
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                </div>
                <p class="text-gray-700">
                    "L'interface est intuitive et les fonctionnalités sont parfaitement adaptées aux besoins des chercheurs d'emploi."
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
            Prêt à commencer ?
        </h2>
        <p class="text-xl mb-8 text-purple-100">
            Rejoignez des milliers de professionnels et trouvez votre prochaine opportunité
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register.choice') }}" class="btn btn-lg bg-white text-purple-600 hover:bg-purple-50">
                <i class="fas fa-rocket mr-2"></i>
                Commencer maintenant
            </a>
            <a href="#" class="btn btn-lg border-2 border-white text-white hover:bg-white hover:text-purple-600">
                <i class="fas fa-info-circle mr-2"></i>
                En savoir plus
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .feature-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .testimonial-card {
        position: relative;
    }
    
    .testimonial-card::before {
        content: '"';
        position: absolute;
        top: -20px;
        left: 20px;
        font-size: 4rem;
        color: #9333ea;
        opacity: 0.1;
    }
</style>
@endpush

@push('scripts')
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

    // Animate numbers on scroll
    function animateNumbers() {
        const numbers = document.querySelectorAll('.text-3xl.font-bold');
        numbers.forEach(number => {
            const target = parseInt(number.textContent.replace(/[^0-9]/g, ''));
            const suffix = number.textContent.replace(/[0-9]/g, '');
            let current = 0;
            const increment = target / 50;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                number.textContent = Math.floor(current) + suffix;
            }, 30);
        });
    }

    // Trigger animation when section is visible
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateNumbers();
                observer.disconnect();
            }
        });
    });

    const statsSection = document.querySelector('.bg-white\\/10');
    if (statsSection) {
        observer.observe(statsSection);
    }
</script>
@endpush
