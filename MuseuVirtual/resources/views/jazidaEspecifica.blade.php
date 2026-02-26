<x-layouts.BaseLayout>
    @vite(['resources/css/EspecificoBlade.css', 'resources/js/app.js'])
    <x-slot name="title">Jazida - {{ $jazida->localizacao }}</x-slot>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    
    <script>
        Fancybox.bind("[data-fancybox]", {
            hideScrollbar: false,
            Toolbar: {
                display: {
                    left: ["infobar"],
                    middle: [],
                    right: ["slideshow", "download", "thumbs", "close"],
                },
            },
        });
    </script>

    <style>

    </style>

    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="2xl:px-80 xl:px-32 lg:px-20 md:px-10 px-4">
        <br><br>

        <div class="hero-section fade-in">
            <h1><strong>Jazida de {{ $jazida->localizacao }}</strong></h1>
                @if($jazida->descricao)
                    @php
                        $descricaoLimpa = strip_tags($jazida->descricao);
                        $descricaoDecodificada = html_entity_decode($descricaoLimpa, ENT_QUOTES, 'UTF-8');
                        $descricaoLimitada = mb_strlen($descricaoDecodificada, 'UTF-8') > 200 ? mb_substr($descricaoDecodificada, 0, 200, 'UTF-8') . '...' : $descricaoDecodificada;
                    @endphp
                    <p>{{ $descricaoLimitada }}</p>
                @endif
            </p>
        </div>

        <div class="section-container fade-in animate-delay-1">
            @php
                $fotoCapa = $jazida->fotos->firstWhere('capa', true) ?? $jazida->fotos->first();
            @endphp
            @if ($fotoCapa)
                <div class="image-gallery-container">
                    <div class="main-image-wrapper">
                        <img id="main-jazida-image" src="{{ asset('storage/' . $fotoCapa->caminho) }}" alt="Foto principal da jazida {{ $jazida->localizacao }}">
                        <div class="action-buttons">
                            <div class="action-button" onclick="downloadImage()" title="Baixar imagem">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 16l-4-4h3V4h2v8h3l-4 4zM6 20v-2h12v2H6z"/>
                                </svg>
                            </div>
                            <div class="action-button" onclick="showQRCode()" title="Gerar QR Code">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm10 0h2v2h-2v-2zm4 0h2v2h-2v-2zm-4 4h2v2h-2v-2zm4 0h2v2h-2v-2zm-6-6h2v2h-2v-2zm2 2h2v2h-2v-2zm0 2h2v2h-2v-2z"/>
                                </svg>
                            </div>
                            <div class="action-button" onclick="openFullscreen()" title="Tela cheia">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M5 5h5V3H3v7h2V5zm9-2v2h5v5h2V3h-7zM3 14v7h7v-2H5v-5H3zm16 0v5h-5v2h7v-7h-2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($jazida->fotos->count() > 1)
                <div class="swiper-container swiper-jazida-thumbs">
                    <div class="swiper-wrapper">
                        @foreach ($jazida->fotos as $foto)
                            <div class="swiper-slide">
                                <img src="{{ asset('storage/' . $foto->caminho) }}" alt="Miniatura da jazida {{ $jazida->localizacao }}" data-src="{{ asset('storage/' . $foto->caminho) }}">
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

        @if($jazida->descricao)
            <div class="section-container fade-in animate-delay-2">
                <div class="content-box">
                    <p><strong>Descrição:</strong> {!! $jazida->descricao !!}</p>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Loading screen
        window.addEventListener('load', function() {
            const loadingOverlay = document.getElementById('loading-overlay');
            setTimeout(() => {
                loadingOverlay.style.opacity = '0';
                setTimeout(() => {
                    loadingOverlay.style.display = 'none';
                }, 500);
            }, 800);
        });

        // Swiper and main functionality
        document.addEventListener('DOMContentLoaded', function () {
            const mainImage = document.getElementById('main-jazida-image');
            
            @if($jazida->fotos->count() > 1)
                const thumbsSwiper = new Swiper(".swiper-jazida-thumbs", {
                    spaceBetween: 15,
                    slidesPerView: 5,
                    freeMode: true,
                    watchSlidesProgress: true,
                    loop: true,
                    centeredSlides: false,
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    breakpoints: {
                        320: { slidesPerView: 2.5, spaceBetween: 10 },
                        480: { slidesPerView: 3, spaceBetween: 12 },
                        768: { slidesPerView: 4, spaceBetween: 15 },
                        1024: { slidesPerView: 5, spaceBetween: 15 },
                    },
                });

                // Handle thumbnail clicks
                thumbsSwiper.on('click', function(swiper, event) {
                    const clickedSlide = event.target.closest('.swiper-slide');
                    if (clickedSlide) {
                        const newSrc = clickedSlide.querySelector('img').getAttribute('data-src');
                        if (newSrc) {
                            mainImage.style.opacity = '0.5';
                            mainImage.src = newSrc;
                            
                            mainImage.onload = function() {
                                mainImage.style.opacity = '1';
                            };
                            
                            document.querySelectorAll('.swiper-slide').forEach(slide => {
                                slide.classList.remove('swiper-slide-thumb-active');
                            });
                            clickedSlide.classList.add('swiper-slide-thumb-active');
                        }
                    }
                });
            @endif

            // Animate elements on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.section-container').forEach(el => {
                observer.observe(el);
            });
        });
        
        // Download image function
        function downloadImage() {
            const imageSrc = document.getElementById('main-jazida-image').src;
            const a = document.createElement('a');
            a.href = imageSrc;
            a.download = 'jazida-{{ Str::slug($jazida->localizacao) }}-' + Date.now();
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            
            showNotification('📥 Download da imagem iniciado!');
        }
        
        // QR Code functions
        function showQRCode() {
            const modal = document.getElementById('qrcode-modal-overlay');
            const qrcodeContainer = document.getElementById('qrcode');
            modal.style.display = 'flex';
            
            qrcodeContainer.innerHTML = '';
            
            const jazidaUrl = window.location.href;
            new QRCode(qrcodeContainer, {
                text: jazidaUrl,
                width: 200,
                height: 200,
                colorDark: "#1c1f1a",
                colorLight: "#F1EEDD",
                correctLevel: QRCode.CorrectLevel.H
            });
        }
        
        function hideQRCode() {
            const modal = document.getElementById('qrcode-modal-overlay');
            modal.style.display = 'none';
        }

        // Download QR Code function
        function downloadQRCode() {
            const qrcodeContainer = document.getElementById('qrcode');
            const canvas = qrcodeContainer.querySelector('canvas');
            
            if (canvas) {
                const imageDataURL = canvas.toDataURL("image/png");
                const a = document.createElement('a');
                a.href = imageDataURL;
                a.download = 'qrcode-jazida-{{ Str::slug($jazida->localizacao) }}';
                
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);

                showNotification('✅ QR Code baixado!');
            } else {
                showNotification('Gerando QR Code... por favor, tente novamente.', 'error');
                showQRCode();
            }
        }

        // Fullscreen function
        function openFullscreen() {
            const img = document.getElementById('main-jazida-image');
            if (img.requestFullscreen) {
                img.requestFullscreen();
            } else if (img.webkitRequestFullscreen) {
                img.webkitRequestFullscreen();
            } else if (img.msRequestFullscreen) {
                img.msRequestFullscreen();
            }
        }

        // Notification system
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: var(--accent-green);
                color: var(--primary-dark);
                padding: 15px 25px;
                border-radius: 10px;
                font-weight: 600;
                z-index: 10000;
                animation: slideInRight 0.3s ease;
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease forwards';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Close modal when clicking outside
        document.getElementById('qrcode-modal-overlay').addEventListener('click', function(e) {
            if (e.target === this) {
                hideQRCode();
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideQRCode();
            }
        });
    </script>
</x-layouts.BaseLayout>