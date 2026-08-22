(function (window, document) {
    'use strict';

    var storageKey = 'portfolio-theme';
    var darkTheme = 'dark';
    var lightTheme = 'light';

    function readTheme() {
        try {
            return window.localStorage.getItem(storageKey) === darkTheme ? darkTheme : lightTheme;
        } catch (error) {
            return lightTheme;
        }
    }

    function saveTheme(theme) {
        try {
            window.localStorage.setItem(storageKey, theme);
        } catch (error) {
            // Storage can be disabled without preventing the toggle from working.
        }
    }

    function applyTheme(theme, button) {
        var isDark = theme === darkTheme;
        document.body.classList.toggle('bg-dark', isDark);
        button.setAttribute('aria-pressed', isDark ? 'true' : 'false');
        button.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
        button.innerHTML = isDark
            ? '<i class="fas fa-sun" aria-hidden="true"></i><span>Light mode</span>'
            : '<i class="fas fa-moon" aria-hidden="true"></i><span>Dark mode</span>';
    }

    function initialize() {
        if (document.body.getAttribute('data-theme-ready') === 'true') {
            return;
        }

        var button = document.getElementById('theme-toggle');
        if (!button) {
            button = document.createElement('button');
            button.type = 'button';
            button.className = 'theme-toggle';
            document.body.appendChild(button);
        }
        button.setAttribute('data-theme-bound', 'true');
        button.addEventListener('click', function () {
            var nextTheme = document.body.classList.contains('bg-dark') ? lightTheme : darkTheme;
            saveTheme(nextTheme);
            applyTheme(nextTheme, button);
        });
        applyTheme(readTheme(), button);
        document.body.setAttribute('data-theme-ready', 'true');
    }

    window.PortfolioFeatures = window.PortfolioFeatures || {};
    window.PortfolioFeatures.theme = initialize;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize, { once: true });
    } else {
        initialize();
    }
}(window, document));
