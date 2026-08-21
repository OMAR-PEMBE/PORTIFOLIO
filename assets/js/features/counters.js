(function (window) {
    'use strict';
    window.PortfolioFeatures = window.PortfolioFeatures || {};
    window.PortfolioFeatures.counters = function () {
        var $ = window.jQuery;
        if (!$ || !$.fn.countTo || !$('.timer').length) { return; }
        var count = function () { $('.timer').countTo(); };
        if ($.fn.appear && $('.fun-fact').length) { $('.fun-fact').appear(count, { accY: -100 }); }
        else { count(); }
    };
}(window));
