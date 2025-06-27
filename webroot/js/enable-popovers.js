/**
 * Bootstrap Popover Initializer
 *
 * This script initializes Bootstrap popovers for all elements with the
 * `data-bs-toggle="popover"` attribute. It converts standard HTML elements
 * into interactive popovers that display additional content when triggered.
 *
 * Features:
 * - Automatically initializes all popover elements on page load
 * - Uses Bootstrap's Popover component
 * - Targets elements with data-bs-toggle="popover" attribute
 *
 * When you modify this file, remember to re-run minification and update the related minified file
 */
$(function () {
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
});
