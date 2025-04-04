/**
 * When you modify this file, remember to re-run minification and update the related minified file
 */
$(function () {
    $('a[data-bs-toggle="collapse"]').click(function () {
        let replacement = $('span.toggle-icon', $(this)).data($(this).hasClass('collapsed') ? 'close-icon' : 'open-icon');

        if (!replacement) {
            return;
        }

        $('span.toggle-icon i', $(this)).replaceWith(replacement);
    });
});
