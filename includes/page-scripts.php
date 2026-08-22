<?php
declare(strict_types=1);

function renderPageScripts(bool $home = false, bool $portfolio = false): void
{
    $scripts = [
        'assets/js/jquery-3.6.0.min.js',
        'assets/js/bootstrap.bundle.min.js',
        'assets/js/jquery.appear.js',
        'assets/js/jquery.easing.min.js',
        'assets/js/swiper-bundle.min.js',
        'assets/js/progress-bar.min.js',
        'assets/js/wow.min.js',
        'assets/js/isotope.pkgd.min.js',
        'assets/js/imagesloaded.pkgd.min.js',
        'assets/js/magnific-popup.min.js',
        'assets/js/jquery.waypoints.js',
        'assets/js/count-to.js',
        'assets/js/YTPlayer.min.js',
        'assets/js/validnavs.js',
        'assets/js/gsap.js',
        'assets/js/ScrollTrigger.min.js',
        'assets/js/jquery.lettering.min.js',
        'assets/js/jquery.circleType.js',
        'assets/js/typed.js',
        'assets/js/features/ui.js',
        'assets/js/features/theme.js?v=20260822',
    ];

    if ($home) {
        $scripts = array_merge($scripts, [
            'assets/js/SplitText.min.js',
            'assets/js/features/counters.js',
            'assets/js/features/carousels.js',
            'assets/js/features/contact.js',
        ]);
    }
    if ($portfolio) {
        $scripts[] = 'assets/js/features/portfolio.js';
    }

    $scripts[] = 'assets/js/features/animations.js';
    $scripts[] = 'assets/js/main.js';

    foreach ($scripts as $script) {
        echo '<script src="' . htmlspecialchars($script, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '" defer></script>' . PHP_EOL;
    }
}
