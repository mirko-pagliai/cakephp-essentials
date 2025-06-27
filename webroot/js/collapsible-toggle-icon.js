/**
 * Toggle icon handler for collapsible elements
 *
 * This script manages the behavior of toggle icons within links that have the
 * `data-bs-toggle="collapse"` attribute. It dynamically changes the icon when
 * the element is expanded or collapsed.
 *
 * Features:
 * - Activates on links with data-bs-toggle="collapse" attribute
 * - Looks for span.toggle-icon element within the link
 * - Replaces the icon (<i>) inside the span based on state:
 *   - Uses data-close-icon when an element is expanded
 *   - Uses data-open-icon when an element is collapsed
 *
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
