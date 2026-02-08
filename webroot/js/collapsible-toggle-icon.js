/**
 * Toggle icon handler for collapsible elements
 *
 * This script manages the behavior of toggle icons within links that have the `data-bs-toggle="collapse"` attribute.
 *  It dynamically changes the icon when the element is expanded or collapsed.
 *
 * Features:
 * - Activates on links with the `data-bs-toggle="collapse"` attribute
 * - Looks for `span.toggle-icon` element within the link
 * - Replaces the icon (`<i>`) inside the span based on state:
 *   - Uses `data-close-icon` when an element is expanded
 *   - Uses `data-open-icon` when an element is collapsed
 *
 * In your template file:
 * ```
 * echo $this->Html->script('/cake/essentials/js/collapsible-toggle-icon.js');
 * ```
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
