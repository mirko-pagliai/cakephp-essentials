/**
 * Bootstrap Tooltip Initializer
 *
 * This script initializes Bootstrap tooltips for all elements with the
 * `data-bs-toggle="tooltip"` attribute. It enables the display of small
 * popup hints when users hover over or focus on elements.
 *
 * Features:
 * - Automatically initializes all tooltip elements on page load
 * - Uses Bootstrap's Tooltip component
 * - Targets elements with data-bs-toggle="tooltip" attribute
 *
 * In your template file:
 * ```
 * echo $this->Html->script('/cake/essentials/js/enable-tooltips.min.js');
 * ```
 *
 * When you modify this file, remember to re-run minification and update the related minified file.
 */
$(function () {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
});
