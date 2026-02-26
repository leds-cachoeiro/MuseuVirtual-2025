// --- Global Variables ---
const mainImage = document.getElementById('main-rocha-image') || document.getElementById('main-mineral-image');
const isMineral = mainImage.id === 'main-mineral-image';
const annotationsModal = document.getElementById('annotations-modal-overlay');
const modalImage = document.getElementById('modal-annotation-image');
const annotationsContainer = document.getElementById('annotations-container');
let currentAnnotations = [];
let imageNaturalWidth = 0;
let imageNaturalHeight = 0;

// --- Fancybox ---
Fancybox.bind("[data-fancybox]", {
    hideScrollbar: false,
    Toolbar: { display: { left: ["infobar"], middle: [], right: ["slideshow", "download", "thumbs", "close"] } }
});

// --- Loading Overlay ---
window.addEventListener('load', () => {
    const overlay = document.getElementById('loading-overlay');
    overlay.style.opacity = '0';
    setTimeout(() => overlay.style.display = 'none', 300);
});

// --- Swiper Thumbnails ---
if (document.querySelector('.swiper-mineral-thumbs')) {
    const thumbsSwiper = new Swiper('.swiper-mineral-thumbs', {
        spaceBetween: 15,
        slidesPerView: 5,
        freeMode: true,
        watchSlidesProgress: true,
        loop: false,
        centeredSlides: false,
        navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
        breakpoints: { 320: { slidesPerView: 2.5, spaceBetween: 10 }, 480: { slidesPerView: 3, spaceBetween: 12 }, 768: { slidesPerView: 4, spaceBetween: 15 }, 1024: { slidesPerView: 5, spaceBetween: 15 } }
    });

    thumbsSwiper.on('click', (swiper, event) => {
        const slide = event.target.closest('.swiper-slide');
        if (!slide) return;

        const newSrc = slide.querySelector('img').dataset.src;
        const newPath = slide.querySelector('img').dataset.path;

        if (newSrc && newPath) {
            mainImage.style.opacity = '0.5';
            mainImage.src = newSrc;
            mainImage.setAttribute('data-current-path', newPath);

            const mainLink = document.getElementById('main-image-link');
            if (mainLink) mainLink.href = newSrc;

            mainImage.onload = () => mainImage.style.opacity = '1';

            document.querySelectorAll('.swiper-slide').forEach(s => s.classList.remove('swiper-slide-thumb-active'));
            slide.classList.add('swiper-slide-thumb-active');
        }
    });
}

// --- Fullscreen ---
window.openFullscreen = () => mainImage?.requestFullscreen?.() || mainImage?.webkitRequestFullscreen?.() || mainImage?.msRequestFullscreen?.();

// --- Download Image ---
window.downloadImage = () => {
    const a = document.createElement('a');
    a.href = mainImage.src;
    a.download = `${isMineral ? 'mineral' : 'rocha'}-${Date.now()}`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    showNotification('📥 Download da imagem iniciado!');
}

// --- QR Code ---
window.showQRCode = () => {
    const modal = document.getElementById('qrcode-modal-overlay');
    modal.style.display = 'flex';
    const qrcodeContainer = document.getElementById('qrcode');
    qrcodeContainer.innerHTML = '';
    new QRCode(qrcodeContainer, { text: window.location.href, width: 200, height: 200, colorDark: "#1c1f1a", colorLight: "#F1EEDD", correctLevel: QRCode.CorrectLevel.H });
}
window.hideQRCode = () => document.getElementById('qrcode-modal-overlay').style.display = 'none';
window.downloadQRCode = () => {
    const canvas = document.getElementById('qrcode')?.querySelector('canvas');
    if (!canvas) { showNotification('Gerando QR Code...'); showQRCode(); return; }
    const a = document.createElement('a');
    a.href = canvas.toDataURL("image/png");
    a.download = `qrcode-${isMineral ? 'mineral' : 'rocha'}-${Date.now()}`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    showNotification('✅ QR Code baixado!');
}

// --- Notifications ---
window.showNotification = (message) => {
    const n = document.createElement('div');
    n.textContent = message;
    n.style.cssText = `position:fixed;top:20px;right:20px;background:var(--accent-green);color:var(--primary-dark);padding:15px 25px;border-radius:10px;font-weight:600;z-index:10000;animation:slideInRight 0.3s ease;`;
    document.body.appendChild(n);
    setTimeout(() => { n.style.animation = 'slideOutRight 0.3s ease forwards'; setTimeout(() => n.remove(), 300); }, 3000);
}

// --- Annotations ---
window.showCurrentImageAnnotations = () => {
    const path = mainImage.dataset.currentPath;
    const annots = window.fotosComAnotacoes[path] || [];
    if (!annots.length) return showNotification('Esta imagem não possui anotações.');
    showAnnotationsModal(annots);
}

window.showAnnotationsModal = (annots) => {
    modalImage.src = mainImage.src;
    hideQRCode();
    currentAnnotations = annots;
    annotationsContainer.innerHTML = '';
    annotationsModal.style.display = 'flex';
    modalImage.onload = () => {
        imageNaturalWidth = modalImage.naturalWidth;
        imageNaturalHeight = modalImage.naturalHeight;
        renderAnnotations();
    };
    window.addEventListener('resize', renderAnnotations);
}

window.hideAnnotationsModal = () => {
    annotationsModal.style.display = 'none';
    window.removeEventListener('resize', renderAnnotations);
}

function renderAnnotations() {
    if (!modalImage.complete) return;
    const scaleX = modalImage.clientWidth / imageNaturalWidth;
    const scaleY = modalImage.clientHeight / imageNaturalHeight;
    annotationsContainer.innerHTML = '';
    currentAnnotations.forEach(a => {
        const pin = document.createElement('div');
        pin.className = 'annotation-pin';
        pin.style.left = `${a.x * scaleX}px`;
        pin.style.top = `${a.y * scaleY}px`;

        const tooltip = document.createElement('div');
        tooltip.className = 'annotation-tooltip';
        tooltip.innerHTML = `<p>${a.texto}</p>`;

        if ((a.x * scaleX + 208) > annotationsContainer.clientWidth) tooltip.style.right = '8px';
        else tooltip.style.left = '8px';

        pin.appendChild(tooltip);
        annotationsContainer.appendChild(pin);
    });
}

annotationsModal.addEventListener('click', e => { if (e.target === annotationsModal) hideAnnotationsModal(); });

// --- Close modals with Escape ---
document.addEventListener('keydown', e => { if (e.key === 'Escape') { hideQRCode(); hideAnnotationsModal(); } });

// --- Close QR modal by clicking overlay ---
document.getElementById('qrcode-modal-overlay')?.addEventListener('click', e => { if (e.target === e.currentTarget) hideQRCode(); });
