<x-layouts.BaseLayout>
    @vite(['resources/css/rochasBlade.css', 'resources/js/app.js', 'resources/js/rochas.js'])
    <x-slot name="title">Rochas</x-slot>
    
    <div class="xl:px-32 lg:px-20 md:px-10 px-4">
        <br><br>
        <div class="hero-section">
            <h1><strong>Catálogo de Rochas</strong></h1><p>
                Conheça os três principais tipos de rochas em nossa coleção. Descubra como cada uma se forma e suas características únicas.
            </p>
        </div>

        {{-- Rochas Ígneas --}}
        <div class="rock-type-section">
            <div class="rock-description">
                <h2><strong>Rochas Ígneas</strong></h2>
                <p>
                    <strong>Formadas pelo fogo.</strong> As rochas ígneas se formam quando o magma ou lava esfria e endurece. 
                    Podem ser formadas no interior da Terra (como o granito) ou na superfície (como o basalto).
                </p>
            </div>

            <div class="flex items-center justify-center pb-8 gap-4">
                <div class="swiper-button-prev swiper-prev-ignea"></div>
                <div class="swiper-button-next swiper-next-ignea">
                </div>
            </div>

            <div class="swiper-container-wrapper">
                <div class="swiper SwiperIgneas">
                    <div class="swiper-wrapper">
                        @foreach ($rochastipo1 as $item)
                            <div class="swiper-slide">
                                {{-- @dd($item) --}}
                                <a href="{{ route('site.rochas.show', [$item->tipo_nome, $item->slug]) }}">
                                    <figure>
                                        @php
                                            $fotoExibir = $item->fotos->firstWhere('capa', true) ?? $item->fotos->first();
                                        @endphp
                                        @if ($fotoExibir)
                                            <img src="{{ asset('storage/' . $fotoExibir->caminho) }}" alt="Imagem da rocha {{ $item->nome }}">
                                        @else
                                            <img src="{{ asset('assets/img/placeholder.png') }}" alt="Nenhuma imagem disponível">
                                        @endif
                                        <figcaption>
                                            <h4><strong>{{ $item->nome }}</strong></h4>
                                        </figcaption>
                                    </figure>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('site.rochas.tipo', 1) }}" class="ver-mais-btn">
                    Conheça todas →
                </a>
            </div>
        </div>

        {{-- Rochas Metamórficas --}}
        <div class="rock-type-section">
            <div class="rock-description">
                <h2><strong>Rochas Metamórficas</strong></h2>
                <p>
                    <strong>Transformadas pelo calor e pressão.</strong> Estas rochas se formam quando rochas existentes 
                    são modificadas por alta temperatura e pressão no interior da Terra. O mármore e o gnaisse são exemplos comuns.
                </p>
            </div>

            <div class="flex items-center justify-center pb-8 gap-4">
                <div class="swiper-button-prev swiper-prev-metamorf"></div>
                <div class="swiper-button-next swiper-next-metamorf"></div>
            </div>

            <div class="swiper-container-wrapper">
                <div class="swiper SwiperMetamorficas">
                    <div class="swiper-wrapper">
                        @foreach ($rochastipo2 as $item)
                            <div class="swiper-slide">
                                <a href="{{ route('site.rochas.show', [$item->tipo_nome, $item->slug]) }}">
                                    <figure>
                                        @php
                                            $fotoExibir = $item->fotos->firstWhere('capa', true) ?? $item->fotos->first();
                                        @endphp
                                        @if ($fotoExibir)
                                            <img src="{{ asset('storage/' . $fotoExibir->caminho) }}" alt="Imagem da rocha {{ $item->nome }}">
                                        @else
                                            <img src="{{ asset('assets/img/placeholder.png') }}" alt="Nenhuma imagem disponível">
                                        @endif
                                        <figcaption>
                                            <h4><strong>{{ $item->nome }}</strong></h4>
                                        </figcaption>
                                    </figure>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('site.rochas.tipo', 2) }}" class="ver-mais-btn">
                    Conheça todas →
                </a>
            </div>
        </div>

        {{-- Rochas Sedimentares --}}
        <div class="rock-type-section">
            <div class="rock-description">
                <h2><strong>Rochas Sedimentares</strong></h2>
                <p>
                    <strong>Formadas por sedimentos acumulados.</strong> Estas rochas se formam quando pedaços de outras rochas, 
                    areia e outros materiais se acumulam e se compactam ao longo do tempo. Arenito e calcário são exemplos conhecidos.
                </p>
            </div>

            <div class="flex items-center justify-center pb-8 gap-4">
                <div class="swiper-button-prev swiper-prev-sedim"></div>
                <div class="swiper-button-next swiper-next-sedim"></div>
            </div>

            <div class="swiper-container-wrapper">
                <div class="swiper SwiperSedimentares">
                    <div class="swiper-wrapper">
                        @foreach ($rochastipo3 as $item)
                            <div class="swiper-slide">
                                <a href="{{ route('site.rochas.show', [$item->tipo_nome, $item->slug]) }}">
                                    <figure>
                                        @php
                                            $fotoExibir = $item->fotos->firstWhere('capa', true) ?? $item->fotos->first();
                                        @endphp
                                        @if ($fotoExibir)
                                            <img src="{{ asset('storage/' . $fotoExibir->caminho) }}" alt="Imagem da rocha {{ $item->nome }}">
                                        @else
                                            <img src="{{ asset('assets/img/placeholder.png') }}" alt="Nenhuma imagem disponível">
                                        @endif
                                        <figcaption>
                                            <h4><strong>{{ $item->nome }}</strong></h4>
                                        </figcaption>
                                    </figure>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('site.rochas.tipo', 3) }}" class="ver-mais-btn">
                    Conheça todas →
                </a>
            </div>
        </div>

        <div class="hero-section mt-16">
            <h3 style="font-size: 32px; color: #F1EEDD; margin-bottom: 16px;"><strong>O Ciclo das Rochas</strong></h3>
            <p style="font-size: 16px; color: #F1EEDD; opacity: 0.9; max-width: 800px; margin: 0 auto; line-height: 1.6;">
                As rochas podem se transformar umas nas outras ao longo do tempo. Uma rocha ígnea pode se tornar metamórfica 
                com calor e pressão, e qualquer rocha pode ser quebrada em pedaços que formam novas rochas sedimentares.
            </p>
            <img src="{{ asset('assets/img/ciclo-das-rochas.png') }}" alt="Diagrama do ciclo das rochas" class="rock-cycle-image">
        </div>
    </div>

</x-layouts.BaseLayout>
