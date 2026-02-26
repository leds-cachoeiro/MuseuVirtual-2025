<x-layouts.BaseLayout>
    @vite(['resources/css/EspecificoBlade.css', 'resources/js/app.js','resources/js/rochaemineral_especificos.js'])
    <x-slot name="title">Rocha - {{ $rocha->nome }}</x-slot>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    {{-- Pass all photos with annotations to JavaScript --}}
    <script>
        window.fotosComAnotacoes = @json($rocha);
        window.rochaOrnamental = @json($rocha->ornamental);
        
        Fancybox.bind("[data-fancybox]", {
            width: "90%",
            height: "90%",
            Toolbar: {
                display: {
                    left: ["infobar"],
                    middle: [],
                    right: ["slideshow", "fullScreen", "download", "thumbs", "close"],
                },
            },
        });
    </script>
    
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="2xl:px-80 xl:px-32 lg:px-20 md:px-10 px-4">
        <br><br>

        <div class="hero-section fade-in">
            <h1><strong>{{ $rocha->nome }}</strong></h1>
            <p>
               @if($rocha->descricao)
                    @php
                        $descricaoLimpa = strip_tags($rocha->descricao);
                        $descricaoDecodificada = html_entity_decode($descricaoLimpa, ENT_QUOTES, 'UTF-8');
                        $descricaoLimitada = mb_strlen($descricaoDecodificada, 'UTF-8') > 200 ? mb_substr($descricaoDecodificada, 0, 200, 'UTF-8') . '...' : $descricaoDecodificada;
                    @endphp
                    <p>{{ $descricaoLimitada }}</p>
                @endif
            </p>
        </div>

        <div class="section-container fade-in animate-delay-1">
            @php
                $fotoCapa = $rocha->fotos->firstWhere('capa', true) ?? $rocha->fotos->first();
            @endphp
            @if ($fotoCapa)
                <div class="image-gallery-container">
                    <div class="main-image-wrapper">
                        <a href="{{ asset('storage/' . $fotoCapa->caminho) }}" 
                            data-caption="{{ $rocha->nome }}">
                            <img id="main-rocha-image" src="{{ asset('storage/' . $fotoCapa->caminho) }}" data-fancybox="gallery"
                                alt="Foto principal da rocha {{ $rocha->nome }}">
                        </a>
                        <div class="action-buttons">
                            <div class="action-button" onclick="downloadImage()" title="Baixar imagem">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 16l-4-4h3V4h2v8h3l-4 4zM6 20v-2h12v2H6z" />
                                </svg>
                            </div>
                            <div class="action-button" onclick="showQRCode()" title="Gerar QR Code">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm10 0h2v2h-2v-2zm4 0h2v2h-2v-2zm-4 4h2v2h-2v-2zm4 0h2v2h-2v-2zm-6-6h2v2h-2v-2zm2 2h2v2h-2v-2zm0 2h2v2h-2v-2z" />
                                </svg>
                            </div>
                            <div class="action-button" onclick="showCurrentImageAnnotations()" title="Ver anotações">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                                </svg>
                            </div>
                            
                            {{-- Botão 3D apenas para rochas ornamentais --}}
                            @if($rocha->ornamental)
                                <div class="action-button" onclick="show3DModal()" title="Visualização 3D">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 18.5c-4.18-1.29-7-5.68-7-10.5V8.3l7-3.11v15.31z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if ($rocha->fotos->count() > 1)
                <div class="swiper-container swiper-rocha-thumbs">
                    <div class="swiper-wrapper">
                        @foreach ($rocha->fotos as $foto)
                            <div class="swiper-slide">
                                <img src="{{ asset('storage/' . $foto->caminho) }}" 
                                    alt="Miniatura da rocha {{ $rocha->nome }}"
                                    data-src="{{ asset('storage/' . $foto->caminho) }}"
                                    data-path="{{ $foto->caminho }}">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            @endif
        </div>

        <div id="qrcode-modal-overlay" class="modal-overlay">
            <div class="modal-content">
                <h2>📱 Acesse esta página</h2>
                <div id="qrcode"></div>
                <div class="modal-actions">
                    <button class="modal-close-button" onclick="downloadQRCode()">Baixar QR Code</button>
                    <button class="modal-close-button close-only" onclick="hideQRCode()">Fechar</button>
                </div>
            </div>
        </div>

        {{-- Modal de Anotações --}}
        <div id="annotations-modal-overlay" class="modal-overlay" style="display: none;">
            <div class="modal-content annotations-modal-content">
                <div class="modal-header">
                    <h2>📝 Anotações</h2>
                    <button class="modal-close-button" onclick="hideAnnotationsModal()">&times;</button>
                </div>
                <div class="annotations-body">
                    <div class="annotation-image-wrapper">
                        <img id="modal-annotation-image" src="" alt="Imagem com anotações">
                        <div id="annotations-container" class="annotations-container">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal 3D - Apenas para rochas ornamentais --}}
        @if($rocha->ornamental)
            <div id="3d-modal-overlay" class="modal-overlay" style="display: none;">
                <div class="modal-3d-content">
                    <div class="modal-header">
                        <h2>🔷 Visualização 3D</h2>
                        <button class="modal-close-button" onclick="hide3DModal()">&times;</button>
                    </div>
                    <canvas id="palco">
                        <img src="{{ asset('storage/' . $fotoCapa->caminho) }}" id="image_map_3d" style="opacity: 0;">
                    </canvas>
                </div>
            </div>
        @endif

        @if ($rocha->descricao || $rocha->composicao)
            <div class="section-container fade-in animate-delay-2">
                <div class="content-box">
                    @if ($rocha->descricao)
                        <p><strong>Descrição:</strong> {!! $rocha->descricao !!}</p>
                    @endif

                    @if ($rocha->descricao && $rocha->composicao)
                        <br>
                    @endif

                    @if ($rocha->composicao)
                        <p><strong>Composição:</strong> {!! $rocha->composicao !!}</p>
                    @endif

                    @if ($rocha->composicao && count($rocha->minerais) != 0)
                        <br>
                    @endif

                    @if (count($rocha->minerais) != 0)
                        <p><strong>Minerais associados:</strong>
                        <ul>
                            @foreach($rocha->minerais as $mineral)
                                <li><a href="{{ route('site.minerais.show', ['slug_mineral' => $mineral->slug]) }}">• <u>{{ $mineral->nome }}</u></a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endif
    </div>
    
</x-layouts.BaseLayout>