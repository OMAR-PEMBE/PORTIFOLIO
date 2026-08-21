(function (window, document) {
    'use strict';

    window.PortfolioFeatures = window.PortfolioFeatures || {};
    window.PortfolioFeatures.ui = function () {
        var $ = window.jQuery;
        if (!$) { return; }

        if ($.fn.tooltip) { $('[data-toggle="tooltip"]').tooltip(); }
        if ($.fn.mb_YTPlayer && $('.player').length) { $('.player').mb_YTPlayer(); }

        if (typeof window.WOW === 'function' && document.querySelector('.wow')) {
            new window.WOW({ boxClass: 'wow', animateClass: 'animated', offset: 0, mobile: true, live: true }).init();
        }

        $('.service-style-one-item').on('mouseenter', function () {
            $(this).addClass('active').parent().siblings().find('.service-style-one-item').removeClass('active');
        });

        if ($.fn.circleType) {
            $('.circle-text-item').each(function () {
                var $element = $(this);
                var options = $element.data('circle-text-options');
                if (typeof options === 'string') {
                    try { options = JSON.parse(options); } catch (error) { options = {}; }
                }
                $element.circleType(typeof options === 'object' ? options : {});
            });
        }
    };
}(window, document));
