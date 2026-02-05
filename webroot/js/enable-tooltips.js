/**
 * # Bootstrap Tooltip Initializer
 *
 * A JavaScript script that handles automatic initialization of Bootstrap tooltips on the page.
 *
 * ## Features
 * - Automatic initialization on page load
 * - Uses Bootstrap's Tooltip component
 * - Targets elements with the `tooltip="true"` attribute
 *
 * ## Installation
 *
 * To use this script in your CakePHP project, add the following line to your template file:
 * ```
 * echo $this->Html->script('/cake/essentials/js/enable-tooltips.min.js');
 * ```
 *
 * ## Usage
 *
 * 1. Make sure you have included Bootstrap and this script in your project
 * 2. Add the `tooltip="true"` attribute to any HTML element where you want to show a tooltip
 * 3. Use the `data-bs-title` attribute to specify the tooltip content
 *
 * ## Dependencies
 *
 * - Bootstrap (JavaScript library)
 * - jQuery
 */
$(function () {
    const tooltipTriggerList = document.querySelectorAll('[tooltip="true"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
});
