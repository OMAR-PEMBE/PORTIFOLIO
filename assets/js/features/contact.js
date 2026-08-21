(function (window) {
    'use strict';
    window.PortfolioFeatures = window.PortfolioFeatures || {};
    window.PortfolioFeatures.contact = function () {
        var $ = window.jQuery;
        if (!$) { return; }
        $('.contact-form').on('submit', function (event) {
            event.preventDefault();
            var $form = $(this);
            var $message = $form.find('.alert-msg');
            var $submit = $form.find('[type="submit"]');
            $submit.after('<img src="assets/img/ajax-loader.gif" class="loader" alt="" aria-hidden="true">').prop('disabled', true);
            $.ajax({ url: $form.attr('action'), method: ($form.attr('method') || 'POST').toUpperCase(), data: $form.serialize() })
                .done(function (data) { $message.html(data).slideDown('slow'); })
                .fail(function (xhr) { $message.html(xhr.responseText || '<div class="alert alert-error">Unable to send your message. Please try again.</div>').slideDown('slow'); })
                .always(function () { $form.find('img.loader').remove(); $submit.prop('disabled', false); });
        });
    };
}(window));
