/**
 * When you modify this file, remember to re-run minification and update the related minified file
 */
$(function () {
    $('a[data-bs-toggle="collapse"]').click(function () {
        if (!$(this).data('open-icon') || !$(this).data('close-icon')) {
            return;
        }

        let replacement = $(this).hasClass('collapsed') ? $(this).data('close-icon') : $(this).data('open-icon');
        $('span.toggle-icon i', $(this)).replaceWith(replacement);
    });
});
