//Inicia o AOS
AOS.init();

//Inicia o SWIPE
document.addEventListener('DOMContentLoaded', function () {
    const swiper = new Swiper(".mySwiper", {
        loop: true, // Ativa o loop infinito do carrossel
        slidesPerView: 'auto', // Mostra automaticamente quantos slides cabem na tela
        spaceBetween: 16, // Espaçamento entre os slides em pixels
        navigation: { // Configura os botões de navegação
            nextEl: ".swiper-button-next", // Seletor para o botão "próximo"
            prevEl: ".swiper-button-prev", // Seletor para o botão "anterior"
        },
    });
});