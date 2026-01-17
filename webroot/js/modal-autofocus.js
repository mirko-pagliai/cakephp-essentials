/**
 * Automatically focus the first element marked with [autofocus] when any Bootstrap modal is fully shown.
 *
 * When you modify this file, remember to re-run minification and update the related minified file.
 */
$(function () {
    $(document).on("shown.bs.modal", ".modal", function () {
        const $autofocusElement = $(this).find("[autofocus]").first();

        if ($autofocusElement.length === 0) {
            return;
        }

        $autofocusElement.trigger("focus");
    });
});
