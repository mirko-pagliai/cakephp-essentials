/**
 * Returns the current local date and time formatted for `<input type="datetime-local">`.
 *
 * Output format: `YYYY-MM-DDTHH:mm`
 * Example: `2025-10-20T14:05`
 *
 * Notes:
 * - Uses the browser's local timezone (not UTC).
 * - Drops seconds and milliseconds to match typical datetime-local step=60.
 * - No external dependencies (pure JavaScript).
 *
 * @returns {string} Local date/time string in HTML5 datetime-local format.
 */
function currentLocalDatetime() {
    const d = new Date();
    d.setSeconds(0, 0); // Remove seconds and milliseconds for clean formatting

    // Helper to ensure two-digit components (e.g., 7 → "07")
    function pad(n) {
        return String(n).padStart(2, '0');
    }

    const y = d.getFullYear();
    const m = pad(d.getMonth() + 1); // Months are 0-based
    const day = pad(d.getDate());
    const h = pad(d.getHours());
    const min = pad(d.getMinutes());

    return `${y}-${m}-${day}T${h}:${min}`;
}
