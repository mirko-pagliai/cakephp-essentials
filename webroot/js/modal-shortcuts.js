/**
 * modal-shortcuts.js
 *
 * Provides a reusable utility to bind global keyboard shortcuts to Bootstrap modal dialogs.
 *
 * Each shortcut is explicitly registered via configuration and opens a specific modal when the defined key combination
 *  is pressed.
 *
 * Features:
 *
 * - Supports multiple modals and shortcuts per page
 * - Keyboard-layout independent (uses `event.code`)
 * - Prevents triggering while typing in editable elements
 * - Reuses Bootstrap modal instances
 *
 * Dependencies:
 *
 * - jQuery;
 * - Bootstrap 5 (Modal component).
 */
$(function () {
    /**
     * Registers a global keyboard shortcut that opens a Bootstrap modal.
     * Each call creates an independent shortcut → modal mapping.
     */
    function registerModalShortcut(options) {
        // Resolve modal element from selector
        const $modal = $(options.modalSelector);

        // Abort if the modal does not exist on the current page
        if ($modal.length === 0) {
            return;
        }

        // Reuse an existing Bootstrap instance or create it once
        const modalInstance = bootstrap.Modal.getOrCreateInstance($modal.get(0));

        // Global keydown handler for the configured shortcut
        $(document).on("keydown", function (event) {
            // Ignore auto-repeated keydown events (key held down)
            if (event.repeat === true) {
                return;
            }

            // Ensure modifier keys match exactly the configuration
            if ((options.ctrlKey === true) !== (event.ctrlKey === true)) {
                return;
            }

            if ((options.shiftKey === true) !== (event.shiftKey === true)) {
                return;
            }

            if ((options.altKey === true) !== (event.altKey === true)) {
                return;
            }

            // Match the physical key code, independent of keyboard layout
            if (event.code !== options.keyCode) {
                return;
            }

            // Do not trigger shortcuts while typing in editable elements
            const $target = $(event.target);
            if (
                $target.is("input") === true ||
                $target.is("textarea") === true ||
                $target.prop("isContentEditable") === true
            ) {
                return;
            }

            event.preventDefault();

            // Do nothing if the modal is already open
            if ($modal.hasClass("show") === true) {
                return;
            }

            // Open the modal
            modalInstance.show();
        });
    }

    // Expose the utility globally for page-level configuration
    window.registerModalShortcut = registerModalShortcut;
});
