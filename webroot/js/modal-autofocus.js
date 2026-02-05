/**
 * Automatically focus the first element marked with [autofocus] when any Bootstrap modal is fully shown.
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
