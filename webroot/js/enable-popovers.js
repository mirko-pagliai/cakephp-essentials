/**
 * # Bootstrap Popover Initializer
 *
 * A JavaScript utility that automatically initializes Bootstrap popovers throughout your application.
 *
 * ## Description
 * This script automates the initialization of Bootstrap popovers for all elements that use the `data-bs-toggle="popover"` attribute. It converts standard HTML elements into interactive popovers that can display additional content when triggered.
 *
 * ## Features
 * - Automatic initialization on page load
 * - Uses Bootstrap's Popover component
 * - Targets elements with `data-bs-toggle="popover"` attribute
 * - Zero-configuration required after installation
 *
 * ## Installation
 * To include this script in your CakePHP project, add the following line to your template file:
 * ```
 * echo $this->Html->script('/cake/essentials/js/enable-popovers.min.js');
 * ```
 *
 * ## Usage
 * 1. Include Bootstrap and jQuery in your project
 * 2. Add the script to your page
 * 3. Add the required attributes to any HTML element you want to convert into a popover:
 *
 * ### Required Attributes
 * - `data-bs-toggle="popover"`: Identifies the element as a popover trigger
 * - `data-bs-content`: Contains the text/HTML to be displayed in the popover
 *
 * ### Optional Attributes
 * - `title`: Sets the popover header text
 * - `data-bs-placement`: Specifies the popover position (top, bottom, left, right)
 * - `data-bs-trigger`: Defines the trigger type (click, hover, focus)
 *
 * ## Dependencies
 * - Bootstrap (JavaScript library)
 * - jQuery
 *
 * When you modify this file, remember to re-run minification and update the related minified file.
 */
$(function () {
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
});
