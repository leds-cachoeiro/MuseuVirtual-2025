
document.addEventListener('DOMContentLoaded', function() {
    
    
    const swiperIgneas = new Swiper(".SwiperIgneas", {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 20,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        breakpoints: {
            640: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            1200: { slidesPerView: 3 },
            1800: { slidesPerView: 4 },
        },
        navigation: {
            nextEl: ".swiper-next-ignea",
            prevEl: ".swiper-prev-ignea",
        },
    });

    const swiperMetamorficas = new Swiper(".SwiperMetamorficas", {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 20,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        breakpoints: {
            640: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            1200: { slidesPerView: 3 },
            1800: { slidesPerView: 4 },
        },
        navigation: {
            nextEl: ".swiper-next-metamorf",
            prevEl: ".swiper-prev-metamorf",
        },
    });

    const swiperSedimentares = new Swiper(".SwiperSedimentares", {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 20,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        breakpoints: {
            640: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            1200: { slidesPerView: 3 },
            1800: { slidesPerView: 4 },
        },
        navigation: {
            nextEl: ".swiper-next-sedim",
            prevEl: ".swiper-prev-sedim",
        },
    });
});

