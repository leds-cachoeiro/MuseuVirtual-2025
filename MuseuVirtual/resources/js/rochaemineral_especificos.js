// VARIÁVEIS DE CONTROLE 3D (Definidas no escopo global do módulo)
let is3DInitialized = false;
let scene, camera, renderer, controls, cubo; 
let isUserInteracting = false; 

// --- IMPORTAÇÕES THREE.JS E CONTROLES ---
// Importações Three.js e OrbitControls
import * as THREE from "https://esm.sh/three@0.164.0";
import { OrbitControls } from "https://esm.sh/three@0.164.0/examples/jsm/controls/OrbitControls";
import GUI from 'https://cdn.jsdelivr.net/npm/lil-gui@0.21/+esm';

// Fullscreen function
window.openFullscreen = function () {
    const img = document.getElementById('main-rocha-image') || document.getElementById('main-mineral-image');
    if (img.requestFullscreen) {
        img.requestFullscreen();
    } else if (img.webkitRequestFullscreen) {
        img.webkitRequestFullscreen();
    } else if (img.msRequestFullscreen) {
        img.msRequestFullscreen();
    }
}

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

// Loading screen
window.addEventListener('load', function () {
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
    // Detecta se é rocha ou mineral
    const mainImage = document.getElementById('main-rocha-image') || document.getElementById('main-mineral-image');
    const isMineral = document.getElementById('main-mineral-image') !== null;

    // Define o seletor do swiper baseado no tipo
    const swiperSelector = isMineral ? ".swiper-mineral-thumbs" : ".swiper-rocha-thumbs";

    if (document.querySelector(swiperSelector)) {
        const thumbsSwiper = new Swiper(swiperSelector, {
            spaceBetween: 15,
            slidesPerView: 5,
            freeMode: true,
            watchSlidesProgress: true,
            loop: false, 
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
        thumbsSwiper.on('click', function (swiper, event) {
            const clickedSlide = event.target.closest('.swiper-slide');
            if (clickedSlide && mainImage) {
                const newSrc = clickedSlide.querySelector('img').getAttribute('data-src');
                if (newSrc) {
                    mainImage.style.opacity = '0.5';
                    mainImage.src = newSrc;
                }
            }
        });
        // Handle thumbnail clicks with path tracking
        thumbsSwiper.on('click', function (swiper, event) {
            const clickedSlide = event.target.closest('.swiper-slide');
            if (clickedSlide) {
                const newSrc = clickedSlide.querySelector('img').getAttribute('data-src');
                const newPath = clickedSlide.querySelector('img').getAttribute('data-path');

                if (newSrc && newPath) {
                    mainImage.style.opacity = '0.5';
                    mainImage.src = newSrc;

                    // Update the data-current-path attribute for the main image
                    mainImage.setAttribute('data-current-path', newPath);
                    // Atualiza o link do fancybox também
                    const mainImageLink = document.getElementById('main-image-link');
                    if (mainImageLink) {
                        mainImageLink.href = newSrc;
                    }

                    mainImage.onload = function () {
                        mainImage.style.opacity = '1';
                    };

                    // Remove classe ativa de todos os slides
                    document.querySelectorAll('.swiper-slide').forEach(slide => {
                        slide.classList.remove('swiper-slide-thumb-active');
                    });
                    clickedSlide.classList.add('swiper-slide-thumb-active');
                }
            }
        });
    }

    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function (entries) {
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
window.downloadImage = function () {
    const mainImage = document.getElementById('main-rocha-image') || document.getElementById('main-mineral-image');
    const imageSrc = mainImage.src;
    const a = document.createElement('a');
    a.href = imageSrc;

    // Define o nome do arquivo baseado no tipo
    const isMineral = document.getElementById('main-mineral-image') !== null;
    const prefix = isMineral ? 'mineral' : 'rocha';

    a.download = `${prefix}-${Date.now()}`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);

    showNotification('📥 Download da imagem iniciado!');
}

// QR Code functions
window.showQRCode = function () {
    const modal = document.getElementById('qrcode-modal-overlay');
    const qrcodeContainer = document.getElementById('qrcode');
    modal.style.display = 'flex';

    qrcodeContainer.innerHTML = '';

    const currentUrl = window.location.href;
    new QRCode(qrcodeContainer, {
        text: currentUrl,
        width: 200,
        height: 200,
        colorDark: "#1c1f1a",
        colorLight: "#F1EEDD",
        correctLevel: QRCode.CorrectLevel.H
    });
}

window.hideQRCode = function () {
    const modal = document.getElementById('qrcode-modal-overlay');
    modal.style.display = 'none';
}

// Download QR Code function
window.downloadQRCode = function () {
    const qrcodeContainer = document.getElementById('qrcode');
    const canvas = qrcodeContainer.querySelector('canvas');

    if (canvas) {
        const imageDataURL = canvas.toDataURL("image/png");
        const a = document.createElement('a');
        a.href = imageDataURL;

        // Define o nome do arquivo baseado no tipo
        const isMineral = document.getElementById('main-mineral-image') !== null;
        const prefix = isMineral ? 'mineral' : 'rocha';

        a.download = `qrcode-${prefix}-${Date.now()}`;

        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        showNotification('✅ QR Code baixado!');
    } else {
        showNotification('Gerando QR Code... por favor, tente novamente.', 'error');
        showQRCode();
    }
}

// Notification system
window.showNotification = function (message) {
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
document.addEventListener('DOMContentLoaded', function () {
    const qrModal = document.getElementById('qrcode-modal-overlay');
    if (qrModal) {
        qrModal.addEventListener('click', function (e) {
            if (e.target === this) {
                hideQRCode();
            }
        });
    }
});

// Keyboard navigation
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        hideQRCode();
        hideAnnotationsModal();
        hide3DModal(); // Adicionado para fechar o modal 3D
    }
});

// --- Updated Functions for Dynamic Annotations ---
const annotationsModal = document.getElementById('annotations-modal-overlay');
const modalImage = document.getElementById('modal-annotation-image');
const annotationsContainer = document.getElementById('annotations-container');
let currentAnnotations = [];
let imageNaturalWidth = 0;
let imageNaturalHeight = 0;

// New function to get current image annotations
window.showCurrentImageAnnotations = function () {
    const mainContainer = document.getElementById("main-rocha-image")

    window.fotosComAnotacoes.fotos.forEach((item) => {
        const caminho = item.caminho
        const src = mainContainer.getAttribute("src")
        const matchImage = src.includes(caminho)

        if (matchImage) {
            if (!item.anotacoes.length) {
                showNotification('Esta imagem não possui anotações.');
                return;
            }
            const currentImageAnnotations = item.anotacoes
            showAnnotationsModal(currentImageAnnotations);
        }
    })
}

// Updated showAnnotationsModal function
window.showAnnotationsModal = function (anotacoesData) {
    const mainImage = document.getElementById('main-rocha-image');
    modalImage.src = mainImage.src;

    hideQRCode();
    hide3DModal(); // Garante que o 3D feche

    annotationsContainer.innerHTML = '';
    currentAnnotations = anotacoesData || [];

    annotationsModal.style.display = 'flex';

    modalImage.onload = function () {
        imageNaturalWidth = modalImage.naturalWidth;
        imageNaturalHeight = modalImage.naturalHeight;
        renderAnnotations();
    };

    window.addEventListener('resize', renderAnnotations);
}

window.hideAnnotationsModal = function () {
    annotationsModal.style.display = 'none';
    window.removeEventListener('resize', renderAnnotations);
}

function renderAnnotations() {
    if (!modalImage.complete || modalImage.naturalWidth === 0) return;

    const displayedWidth = modalImage.clientWidth;
    const displayedHeight = modalImage.clientHeight;

    const scaleX = displayedWidth / imageNaturalWidth;
    const scaleY = displayedHeight / imageNaturalHeight;

    annotationsContainer.innerHTML = '';
    
    currentAnnotations.forEach(anotacao => {
        const x = anotacao.x * scaleX;
        const y = anotacao.y * scaleY;

        const pin = document.createElement('div');
        pin.className = 'annotation-pin';
        pin.style.left = `${x}px`;
        pin.style.top = `${y}px`;

        const tooltip = document.createElement('div');
        tooltip.className = 'annotation-tooltip';
        tooltip.innerHTML = `<p>${anotacao.texto}</p>`;

        const containerBounds = annotationsContainer.getBoundingClientRect();
        if (x + 8 + 200 > containerBounds.width) {
            tooltip.style.right = '8px';
        } else {
            tooltip.style.left = '8px';
        }

        pin.appendChild(tooltip);
        annotationsContainer.appendChild(pin);
    });
}

annotationsModal.addEventListener('click', function (e) {
    if (e.target === this) {
        hideAnnotationsModal();
    }
});

// Função adicional para trocar imagem (compatibilidade)
window.changeMainImage = function (imageSrc) {
    const mainImage = document.getElementById('main-rocha-image') || document.getElementById('main-mineral-image');
    const mainImageLink = document.getElementById('main-image-link');

    if (mainImage) {
        mainImage.style.opacity = '0.5';
        mainImage.src = imageSrc;

        if (mainImageLink) {
            mainImageLink.href = imageSrc;
        }

        mainImage.onload = function () {
            mainImage.style.opacity = '1';
        };
    }
}

// =========================================================
// INÍCIO DA LÓGICA 3D - Three.js OTIMIZADA E CORRIGIDA
// =========================================================

// Função de redimensionamento que usa as dimensões do CSS do canvas
function resizeRendererAndCamera() {
    if (renderer && camera) {
        const canvas = renderer.domElement;
        const width = canvas.clientWidth;
        const height = canvas.clientHeight;
        
        const needResize = canvas.width !== width || canvas.height !== height;

        if (needResize) {
            renderer.setSize(width, height, false); 
            camera.aspect = width / height;
            camera.updateProjectionMatrix();
        }
    }
}

// Loop de animação
function animate() {
    requestAnimationFrame(animate);

    if (!is3DInitialized) return; 

    // Rotação automática removida, mantendo o objeto estático
    
    controls.update(); // Necessário para damping e interação manual
    resizeRendererAndCamera(); 
    
    renderer.render(scene, camera);
}


// Função para inicializar a cena 3D (chamada apenas na primeira vez que o modal abre)
function init3DScene() {
    // 1. Configuração do Renderizador e Cena
    scene = new THREE.Scene();
    scene.background = new THREE.Color(0xf7f7f7);
    const canvas = document.getElementById("palco");
    renderer = new THREE.WebGLRenderer({ antialias: true, canvas });
    renderer.shadowMap.enabled = true;
    renderer.outputEncoding = THREE.sRGBEncoding; // Corrige cores

    // 2. Configuração da Câmera
    const fov = 75;
    const aspect = canvas.clientWidth / canvas.clientHeight; 
    const near = 0.1;
    const far = 100;
    camera = new THREE.PerspectiveCamera(fov, aspect, near, far);
    camera.position.set(3, 3, 5); 
    camera.lookAt(0,0,0);

    // 3. Luzes
    const colorLight = 0xFFFFFF;
    const intensity = 3;
    
    // Luz Direcional (Simula o sol)
    const ligth = new THREE.DirectionalLight(colorLight, intensity);
    ligth.position.set(5, 10, 5); 
    ligth.castShadow = true; // Permite gerar sombras
    scene.add(ligth);

    // Configuração de sombras da luz (Qualidade 1024)
    ligth.shadow.mapSize.width = 1024;  
    ligth.shadow.mapSize.height = 1024;
    ligth.shadow.camera.near = 0.5;
    ligth.shadow.camera.far = 50;
    ligth.shadow.camera.left = -10;
    ligth.shadow.camera.right = 10;
    ligth.shadow.camera.top = 10;
    ligth.shadow.camera.bottom = -10;

    // Point Light (Ponto de luz extra)
    const pl = new THREE.PointLight(colorLight, intensity);
    pl.position.set(-2, 2, 0);
    scene.add(pl);

    // LUZ AMBIENTE (Melhora a visualização das áreas escuras)
    const ambientLight = new THREE.AmbientLight(0xffffff, 1.5); 
    scene.add(ambientLight);
    
    // 4. Geometria (O Cubo/Rocha)
    const constante_proporcional = 2.5;
    const boxWidth = 0.05 * constante_proporcional;
    const boxHeight = 1 * constante_proporcional;
    const boxDepth = 2 * constante_proporcional;

    const image = document.getElementById("image_map_3d");
    const url_image = image ? image.getAttribute('src'):'';

    const loader = new THREE.TextureLoader();
    const texture = loader.load(url_image); 
    texture.colorSpace = THREE.SRGBColorSpace;

    const geometry = new THREE.BoxGeometry(boxWidth, boxHeight, boxDepth);

    // MeshStandardMaterial: OTIMIZADO para texturas e iluminação
    const material = new THREE.MeshStandardMaterial({
        map: texture,
        metalness: 0.1, 
        roughness: 0.8,
    });

    cubo = new THREE.Mesh(geometry, material);
    cubo.position.y = 1
    cubo.castShadow = true;  // Cubo projeta sombra
    cubo.receiveShadow = true; 
    scene.add(cubo);

    // 5. O Chão (Plane)
    const planeSize = 40;
    const planeGeo = new THREE.PlaneGeometry(planeSize, planeSize);
    const planeMat = new THREE.MeshStandardMaterial({
        color:0xFFFFFF,
        side: THREE.DoubleSide,
    });
    const mesh = new THREE.Mesh(planeGeo, planeMat);
    mesh.rotation.x = Math.PI * -.5;
    mesh.position.y = -0.5; 
    mesh.receiveShadow = true; // Chão recebe sombra
    scene.add(mesh);

    // 6. Controles (Limites de navegação)
    controls = new OrbitControls(camera, canvas);
    controls.target.set(0, 0, 0); 
    controls.enableDamping = true; 
    controls.dampingFactor = 0.05;
    controls.minDistance = 2; 
    controls.maxDistance = 15; 
    
    // NOVO: Limita o ângulo para que a câmera não vá muito para baixo
    controls.maxPolarAngle = 1.45; // ~83 graus, impede que a câmera vá além do chão
    
    controls.update();

    // Eventos para detectar quando o usuário começa e para de interagir
    controls.addEventListener('start', () => {
        isUserInteracting = true;
    });

    controls.addEventListener('end', () => {
        isUserInteracting = false;
    });

    resizeRendererAndCamera();
    
    is3DInitialized = true;
}

// ----------------------------------------------------
// Funções de controle do Modal 3D (chamadas pelo HTML)
// ----------------------------------------------------

window.show3DModal = function () {
    const modalOverlay = document.getElementById('3d-modal-overlay');
    if (modalOverlay) {
        modalOverlay.style.display = 'flex';
    }
    
    // Inicializa a cena 3D apenas na primeira vez
    if (!is3DInitialized) {
        init3DScene();
    } else {
        // Garante o redimensionamento e atualização dos controles ao reabrir
        resizeRendererAndCamera();
        controls.update();
    }
    
    // Esconde outros modais
    hideQRCode();
    hideAnnotationsModal();
}

window.hide3DModal = function () {
    const modalOverlay = document.getElementById('3d-modal-overlay');
    if (modalOverlay) {
        modalOverlay.style.display = 'none';
    }
}

// Inicia o loop de animação globalmente (ele só renderizará se is3DInitialized for true)
animate();