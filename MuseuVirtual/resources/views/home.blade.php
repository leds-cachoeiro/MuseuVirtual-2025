<x-layouts.BaseLayout>
    @vite(['resources/css/homeBlade.css', 'resources/js/app.js'])
    <x-slot name="title">Home</x-slot>

    <!-- Loading Screen -->
    <div class="loading-overlay" id="loading">
        <div class="spinner"></div>
    </div>
    @if (isset($termo))
        <main id="resultados-container" class="results-section mt-4 max-w-7xl mx-auto px-4 py-6 rounded shadow text-white">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-black lato">
                    Resultados da busca por: <span class="text-white-600">"{{ $termo }}"</span>
                </h2>
                <button onclick="document.getElementById('resultados-container').style.display='none'"
                    class="text-sm text-red-600 bg-[#524c4c] px-3 py-1 rounded-full transition hover:bg-red-400 hover:text-white">
                    Fechar tudo
                </button>
            </div>

            @if ($minerais->count() || $rochas->count() || $jazidas->count())
                @if ($minerais->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold font-cinzel mb-2">Minerais encontrados:</h3>
                        <ul class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($minerais as $mineral)
                                <li>
                                    <a href="{{ route('site.minerais.show', $mineral->slug) }}"
                                        class="text-white px-4 py-2 inline-block text-white px-3 py-1 rounded-full leading-snug break-words max-w-full opacity-90 bg-[#565851] hover:bg-[#898f7a] transition">
                                        {{ $mineral->nome }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-4">
                            {{ $minerais->appends(['q' => $termo])->links() }}
                        </div>
                    </div>
                @endif

                @if ($rochas->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold font-cinzel mb-2">Rochas encontradas:</h3>
                        <ul class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($rochas as $rocha)
                                <li>
                                    <a href="{{ route('site.rochas.show', [$rocha->tipo,$rocha->slug]) }}"
                                        class="text-white px-4 py-2 inline-block text-white px-3 py-1 rounded-full leading-snug break-words max-w-full opacity-90 bg-[#565851] hover:bg-[#898f7a] transition">
                                        {{ $rocha->nome }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-4">
                            {{ $rochas->appends(['q' => $termo])->links() }}
                        </div>
                    </div>
                @endif

                @if ($jazidas->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold font-cinzel mb-2">Jazidas encontradas:</h3>
                        <ul class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($jazidas as $jazida)
                                <li>
                                    <a href="{{ route('jazidas.show', $jazida->id) }}"
                                        class="text-white px-4 py-2 inline-block text-white px-3 py-1 rounded-full leading-snug break-words max-w-full opacity-90 bg-[#565851] hover:bg-[#898f7a] transition">
                                        {{ $jazida->localizacao }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-4">
                            {{ $jazidas->appends(['q' => $termo])->links() }}
                        </div>
                    </div>
                @endif
            @else
                <p class="text-gray-600 font-cinzel">Nenhum resultado encontrado para "{{ $termo }}".</p>
            @endif
        </main>
    @endif

    <!-- Hero Section -->
    <div class="hero-container">
        <div class="hero-content">
            <br>
            <h1 class="main-title">Museu Virtual ES</h1>
            <p class="subtitle">
                Bem-vindo ao Museu Virtual das Rochas, um espaço interativo e educativo
                dedicado à incrível diversidade geológica da nossa região capixaba.
            </p>
        </div>

        <!-- Imagem Hero -->
        <figure class="hero-image-container">
            <img src="/assets/img/domingos-martins-pedra-azul23.JPG"
                alt="Rochas Geologia"
                class="hero-image">
        </figure>
    </div>

    <!-- Explore Section -->
    <div class="explore-section">
        <h2 class="explore-title">Explore</h2>
        <h3 class="explore-intro">
            Explore o universo da geologia em nosso site, onde você encontra informações
            detalhadas sobre jazidas, rochas e minerais.
        </h3>

        <div class="cards-container">
            <div class="cards-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
                    <figure class="card-figure" data-aos="fade-right">
                    <a href="{{ route('site.jazidas') }}" class="card-link">
                        <img class="card-image" src="/assets/img/JAZIDAinicial(2).png" alt="Jazidas">
                        <div class="card-content">
                            <h2 class="card-title">Jazidas</h2>
                            <h3 class="card-description">
                                Confira aqui nosso acervo completo de jazidas minerais,
                                com informações detalhadas sobre sua formação e localização.
                            </h3>
                        </div>
                    </a>
                </figure>

                <figure class="card-figure" data-aos="fade-left">
                    <a href="{{ route('site.rochas') }}" class="card-link">
                        <img class="card-image" src="/assets/img/rochaINICIAL.jpg" alt="Rochas">
                        <div class="card-content">
                            <h2 class="card-title">Rochas</h2>
                            <h3 class="card-description">
                                Descubra a diversidade de rochas da nossa região,
                                desde ígneas até metamórficas e sedimentares.
                            </h3>
                        </div>
                    </a>
                </figure>

                <figure class="card-figure" data-aos="fade-right">
                    <a href="{{ route('site.minerais') }}" class="card-link">
                        <img class="card-image" src="/assets/img/MINERALinicial.jpg" alt="Minerais">
                        <div class="card-content">
                            <h2 class="card-title">Minerais</h2>
                            <h3 class="card-description">
                                Explore nossa coleção de minerais únicos,
                                com suas propriedades físicas e químicas detalhadas.
                            </h3>
                        </div>
                    </a>
                </figure>


                <figure class="card-figure" data-aos="fade-left">
                    <a href="{{ route('site.rochasOrnamentais') }}" class="card-link">
                        <img class="card-image" src="/assets/img/ORNAMENTALinicial.jpg" alt="Rochas Ornamentais">
                        <div class="card-content">
                            <h2 class="card-title">Rochas Ornamentais</h2>
                            <h3 class="card-description">
                                Conheça as rochas ornamentais da região capixaba,
                                suas aplicações e importância econômica para o estado.
                            </h3>
                        </div>
                    </a>
                </figure>
            </div>
        </div>
    </div>

    <!-- CSS para a imagem hero -->
    <style>
        .hero-image-container {
            width: 90%;
            max-width: 1200px;
            height: 720px;
            margin: 2rem auto;
            padding: 0;
            display: block;
            overflow: hidden;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .hero-image {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            object-position: center;
        }

        /* Responsividade */
        @media (max-width: 1024px) {
            .hero-image-container {
                width: 85%;
                height: 400px;
                border-radius: 20px;
            }
        }

        @media (max-width: 768px) {
            .hero-image-container {
                width: 90%;
                height: 320px;
                border-radius: 16px;
                margin: 1.5rem auto;
            }
        }

        @media (max-width: 480px) {
            .hero-image-container {
                width: 95%;
                height: 250px;
                border-radius: 12px;
                margin: 1rem auto;
            }
        }
    </style>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.getElementById('loading').classList.add('fade-out');
            }, 300);
        });

        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 1000,
                once: true,
                offset: 100
            });
        }

        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    const scrolled = window.pageYOffset;
                    document.querySelectorAll('.floating-element').forEach(el => {
                        el.style.transform = `translateY(${-scrolled * 0.3}px)`;
                    });
                    ticking = false;
                });
                ticking = true;
            }
        });

        document.querySelectorAll('.card-figure').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease';

            card.addEventListener('mouseenter', () => card.style.transform = 'translateY(-15px) scale(1.02)');
            card.addEventListener('mouseleave', () => card.style.transform = 'translateY(0) scale(1)');
            card.addEventListener('click', () => {
                card.style.transform = 'translateY(-5px) scale(0.98)';
                setTimeout(() => card.style.transform = 'translateY(-15px) scale(1.02)', 150);
            });
        });

        window.addEventListener('load', () => {
            const cards = document.querySelectorAll('.card-figure');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 1200 + (index * 200));
            });
        });
    </script>
</x-layouts.BaseLayout>