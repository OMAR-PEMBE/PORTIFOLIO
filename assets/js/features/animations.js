(function (window, document) {
    'use strict';

    window.PortfolioFeatures = window.PortfolioFeatures || {};
    window.PortfolioFeatures.animations = function () {
        var gsap = window.gsap;
        var hero = document.getElementById('js-hero');
        var ticking = false;

        if (hero) {
            window.addEventListener('scroll', function () {
                if (ticking) { return; }
                ticking = true;
                window.requestAnimationFrame(function () {
                    hero.style.width = (100 + window.scrollY / 18) + '%';
                    ticking = false;
                });
            }, { passive: true });
        }

        if (!gsap) { return; }
        if (window.ScrollTrigger) { gsap.registerPlugin(window.ScrollTrigger); }
        if (document.querySelector('.upDownScrol')) {
            gsap.set('.upDownScrol', { yPercent: 105 });
            gsap.to('.upDownScrol', { yPercent: -105, ease: 'none', scrollTrigger: { trigger: '.upDownScrol', end: 'bottom center', scrub: 1 } });
        }
        if (typeof window.SplitText === 'function') {
            document.querySelectorAll('.split-text').forEach(function (element) {
                var split = new window.SplitText(element, { type: 'lines, words', linesClass: 'line' });
                gsap.timeline({ ease: 'power4', scrollTrigger: { trigger: element, start: 'top 90%' } })
                    .from(split.words, { yPercent: 100, stagger: 0.008 });
            });
        }

        var preloaderPath = document.getElementById('preloaderSvg');
        if (!preloaderPath) { return; }
        var introText = document.querySelector('.hero-section .intro_text svg text');
        var timeline = gsap.timeline({ onComplete: function () { if (introText) { introText.classList.add('animate-stroke'); } } });
        timeline.to('.preloader-heading .load-text, .preloader-heading .cont', { delay: 1.5, y: -100, opacity: 0 })
            .to(preloaderPath, { duration: 0.5, attr: { d: 'M0 502S175 272 500 272s500 230 500 230V0H0Z' }, ease: 'power2.easeIn' })
            .to(preloaderPath, { duration: 0.5, attr: { d: 'M0 2S175 1 500 1s500 1 500 1V0H0Z' }, ease: 'power2.easeOut' })
            .to('.preloader', { y: -1500 })
            .to('.preloader', { zIndex: -1, display: 'none' });
    };
}(window, document));
