/**
 * Form Button Disable Handler
 *
 * This script prevents multiple form submissions by temporarily disabling
 * submit buttons after the first click. The buttons are automatically
 * re-enabled after a safety timeout.
 *
 * Features:
 * - Automatically disables submit buttons on form submission
 * - Prevents accidental multiple clicks
 * - Re-enables buttons after 8 seconds
 * - Applies to all forms on the page
 *
 * When you modify this file, remember to re-run minification and update the related minified file.
 */
$(function () {
    $('form').on('submit', function () {
        const submit = $(this).find('[type=submit]');
        submit.prop('disabled', true);

        setTimeout(function () {
            submit.prop('disabled', false);
        }, 8000);
    });
});
