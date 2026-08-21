(function (window, document) {
    'use strict';
    window.PortfolioFeatures = window.PortfolioFeatures || {};
    window.PortfolioFeatures.carousels = function () {
        if (typeof window.Swiper !== 'function') { return; }
        if (document.querySelector('.expertise-carousel')) {
            new window.Swiper('.expertise-carousel', { loop: true, slidesPerView: 1, spaceBetween: 30, speed: 1000, autoplay: { delay: 2000, disableOnInteraction: false } });
        }
        if (document.querySelector('.testimonial-style-one-carousel')) {
            new window.Swiper('.testimonial-style-one-carousel', { direction: 'horizontal', loop: true, autoplay: true, pagination: { el: '.swiper-pagination', type: 'bullets', clickable: true }, navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' } });
        }
    };
}(window, document));
