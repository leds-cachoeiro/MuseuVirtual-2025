<x-layouts.BaseLayout>
    <x-slot name="title">JazidaEspecifica</x-slot>

    <body>
        <div class="2xl:px-80 xl:px-32 lg:px-20 md:px-10 ">
            <h1 class="font-[Arial] text-[40px] text-[#F1EEDD] pt-16"><strong>{{ $jazida->localizacao }}</strong></h1>

            @php
                $fotoExibir = null;
                foreach ($jazida->fotos as $item) {
                    if ($item->capa) {
                        $fotoExibir = $item;
                        break;
                    }
                }
                // Se nenhuma capa foi definida, usa a primeira foto disponível
                if (is_null($fotoExibir) && count($jazida->fotos) > 0) {
                    $fotoExibir = $jazida->fotos[0];
                }
            @endphp

            @if ($fotoExibir)
                {{-- Contêiner para centralizar a imagem principal e aplicar estilos --}}
                <div class="px-90 ">
                    <img class="2xl:w-full h-full h-[460px] rounded-xl main-image object-cover"
                        src="{{ asset('storage/' . $fotoExibir->caminho) }}"
                        alt="Imagem principal de {{ $jazida->nome }}">
                </div>
            @endif
            <div class="pt-6">
                <h2 class="text-[20px] font-[arial] text-[#F1EEDD]"> <strong> Descrição:
                    </strong>{!! $jazida->descricao !!}</h2>
                <br>

                <div class="swiper-container-wrapper">
                    <div class="swiper SwiperMinerais">
                        <div class="flex justify-between items-center">
                            <div class="flex">
                                <div class="swiper-button-prev swiper-button-prev-1"></div>
                                <div class="swiper-button-next swiper-button-next-1"></div>
                            </div>
                        </div>
                        <div class="swiper-wrapper">
                            @if ($jazida->minerais->isNotEmpty())
                                <ul class="list-disc">
                                    @foreach ($jazida->minerais as $mineral)
                                        <div class="swiper-slide">
                                            @php
                                                // Tenta achar a capa do mineral
                                                $fotoMineral = null;

                                                if (isset($mineral->fotos)) {
                                                    foreach ($mineral->fotos as $f) {
                                                        if ($f->capa) {
                                                            $fotoMineral = $f;
                                                            break;
                                                        }
                                                    }
                                                    if (is_null($fotoMineral) && $mineral->fotos->count() > 0) {
                                                        $fotoMineral = $mineral->fotos->first();
                                                    }
                                                }

                                                // Monta a URL da imagem (funciona se o arquivo estiver em storage/app/public)
                                                $urlFotoMineral = $fotoMineral
                                                    ? asset('storage/' . ltrim($fotoMineral->caminho, '/'))
                                                    : null;
                                            @endphp

                                            <div class="flex items-center gap-4">
                                                <a href="{{ route('minerais.show', $mineral->id) }}">
                                                    {{-- Imagem do mineral (se houver) --}}
                                                    @if ($urlFotoMineral)
                                                        <img src="{{ $urlFotoMineral }}"
                                                            alt="Imagem de {{ $mineral->nome }}"
                                                            class="object-cover w-[500px] h-[500px] rounded-xl hover:scale-95 duration-300 ">
                                                    @endif
                                    @endforeach
                                </ul>
                            @else
                                <p>Não há minerais cadastrados para esta jazida.</p>
                            @endif

                            {{-- Dados do mineral --}}
                            <div class="text-[25px] font-[arial] text-[#F1EEDD]">
                                <strong>{{ $mineral->nome }}</strong><br>
                            </div>
                        </div>
                    </div>

                    <style>
                        /* Estilos para o contêiner principal do carrossel e seus botões */
                        /* Estilos gerais para o carrossel e seus componentes */
                        .swiper-container-wrapper {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            max-width: 1200px;
                            /* Aumentado de 800px para 1200px */
                            /* Aumentando a largura máxima para acomodar 4 slides de 500px (2000px) + espaçamento */
                            max-width: 2100px;
                            margin: 50px auto;
                            position: relative;
                        }

                        /* Estilos para o próprio carrossel (a área visível dos slides) */
                        .mySwiper,
                        .SwiperRochas {
                            width: 100%;
                            height: 600px;
                            /* Aumentado de 240px para 600px */
                            overflow: hidden;
                        }

                        /* Estilos para cada slide individual do carrossel */
                        /* Removendo a largura fixa do slide para que o Swiper a calcule automaticamente */
                        .swiper-slide {
                            width: 400px;
                            /* Aumentado de 240px para 400px */
                            /* O espaço entre os slides será controlado pelo 'spaceBetween' no JS do Swiper */
                            height: 700px;
                        }

                        /* Estilos para as imagens dentro dos slides do carrossel */
                        .swiper-slide img {
                            width: 100%;
                            height: 100%;
                            width: 500px;
                            height: 500px;
                            object-fit: cover;
                            border-radius: 0.75rem;
                            transition: transform 0.3s ease-in-out;
                        }

                        /* Estilos para os botões de navegação (setas) do carrossel */
                        .swiper-slide img:hover {
                            transform: scale(1.05);
                        }

                        /* Botões de navegação do carrossel */
                        .swiper-button-prev,
                        .swiper-button-next {
                            width: 50px;
                            /* Aumentado de 40px para 50px */
                            height: 50px;
                            /* Aumentado de 40px para 50px */
                            border-radius: 50%;
                            background-color: rgba(0, 0, 0, 0);
                            background-color: transparent;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            cursor: pointer;
                            flex-shrink: 0;
                            position: static;
                            margin: 0 15px;
                            /* Aumentado de 10px para 15px */
                            transition: background-color 0.3s ease;
                        }

                        /* Efeito de HOVER para o fundo dos botões de navegação */
                        .swiper-button-prev:hover,
                        .swiper-button-next:hover {
                            background-color: rgba(0, 0, 0, 0.3);
                            background-color: rgba(255, 255, 255, 0.2);
                        }

                        /* Estilos para as setas (o conteúdo gerado por ::after) dentro dos botões */
                        .swiper-button-prev::after,
                        .swiper-button-next::after {
                            font-size: 24px;
                            /* Aumentado de 20px para 24px */
                            color: #F1EEDD;
                            transition: color 0.3s ease;
                        }

                        /* Efeito de HOVER para a cor das SETAS */
                        .swiper-button-prev:hover::after,
                        .swiper-button-next:hover::after {
                            color: #FFFFFF;
                        }
                    </style>

                    <script>
                        document.addEventListener('DOMContentLoaded', function()) {
                            const swiper = new Swiper(".SwiperMinerais", {
                                loop: true, // Ativa o loop infinito do carrossel
                                slidesPerView: 3, // Mostra 3 slides por vez
                                spaceBetween: 16, // Espaçamento entre os slides
                                navigation: { // Configura os botões de navegação
                                    nextEl: ".swiper-button-next-1", // Botão "próximo"
                                    prevEl: ".swiper-button-prev-1", // Botão "anterior"
                                },
                                breakpoints: {
                                    640: {
                                        slidesPerView: 1
                                    }, // 1 slide em telas menores
                                    768: {
                                        slidesPerView: 2
                                    }, // 2 slides em telas médias
                                    1024: {
                                        slidesPerView: 3
                                    }, // 3 slides em telas maiores
                                },
                            });
                        }
                    </script>
                </div>
            </div>
        </div>
    </body>
</x-layouts.BaseLayout>
