(function (window) {
    'use strict';

    window.PortfolioFeatures = window.PortfolioFeatures || {};
    window.PortfolioFeatures.portfolio = function () {
        var $ = window.jQuery;
        if (!$) { return; }

        var $gallery = $('#gallery-masonary');
        var $masonry = $gallery.add('.blog-masonry');
        if ($masonry.length && $.fn.imagesLoaded && $.fn.isotope) {
            $masonry.imagesLoaded(function () {
                if ($gallery.length) {
                    $gallery.isotope({ itemSelector: '.gallery-item', percentPosition: true, masonry: { columnWidth: '.gallery-item' } });
                }
                $('.blog-masonry').isotope({ itemSelector: '.blog-item', percentPosition: true, masonry: { columnWidth: '.blog-item' } });
            });

            $('.mix-item-menu').on('click', 'button', function (event) {
                event.preventDefault();
                $(this).addClass('active').siblings().removeClass('active');
                if ($gallery.length) { $gallery.isotope({ filter: $(this).attr('data-filter') }); }
            });
        }

        if (!$.fn.magnificPopup) { return; }
        $('.popup-link').magnificPopup({ type: 'image' });
        $('.popup-gallery').magnificPopup({ type: 'image', gallery: { enabled: true } });
        $('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({ type: 'iframe', mainClass: 'mfp-fade', removalDelay: 160, preloader: false, fixedContentPos: false });

        $('.magnific-mix-gallery').each(function () {
            var $container = $(this);
            var $links = $container.find('.item');
            if (!$links.length) { return; }
            var items = $links.map(function () {
                var $item = $(this);
                return { src: $item.attr('href'), type: $item.hasClass('magnific-iframe') ? 'iframe' : 'image', title: $item.data('title') };
            }).get();
            $links.magnificPopup({
                mainClass: 'mfp-fade', items: items, type: 'image',
                gallery: { enabled: true, tPrev: $container.data('prev-text'), tNext: $container.data('next-text') },
                callbacks: { beforeOpen: function () { var index = $links.index(this.st.el); if (index >= 0) { this.goTo(index); } } }
            });
        });
    };
}(window));
