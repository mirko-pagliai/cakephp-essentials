/**
 * Script for automatic OTP form submission
 *
 * This script handles the automatic submission of a form when a 6-character
 * One Time Password (OTP) code is entered into the designated input field.
 *
 * Features:
 * - Monitors the input field with ID 'otp'
 * - Automatically submits the containing form when the input value reaches
 *   6 characters in length
 * - No manual submission (pressing "enter" or clicking buttons) required
 *
 * In your template file:
 * ```
 * echo $this->Html->script('/cake/essentials/js/auth_otp.min.js');
 * ```
 *
 * When you modify this file, remember to re-run minification and update the related minified file.
 */
$(document).ready(function () {
    $('#otp').on("keyup", function () {
        if ($(this).val().length === 6) {
            $(this).closest('form').trigger('submit');
        }
    });
});
