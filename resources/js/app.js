import Swiper from 'swiper';
import { Pagination, Navigation, Autoplay, EffectFade } from 'swiper/modules';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';
import 'swiper/css/effect-fade';

const initSwiper = () => {
    const swiperEl = document.querySelector('.carousel-swiper');
    if (!swiperEl) return;

    new Swiper(swiperEl, {
        modules: [Pagination, Navigation, Autoplay, EffectFade],
        loop: true,
        autoplay: {
            delay: 15000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            bulletClass: 'inline-block w-2 h-2 rounded-full bg-white/50 mx-1 cursor-pointer transition-all',
            bulletActiveClass: '!bg-orange-600 !w-6',
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true,
        },
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSwiper);
} else {
    initSwiper();
}
