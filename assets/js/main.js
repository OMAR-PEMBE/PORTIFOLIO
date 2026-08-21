(function (window, document) {
    'use strict';

    function initialize() {
        var features = window.PortfolioFeatures || {};
        Object.keys(features).forEach(function (name) {
            try {
                features[name]();
            } catch (error) {
                console.error('Unable to initialize the "' + name + '" feature.', error);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize, { once: true });
    } else {
        initialize();
    }
}(window, document));
