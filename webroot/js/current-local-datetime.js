/**
 * Returns the current local date and time as a Date object normalized for ISO formatting.
 *
 * @returns {Date} Local date/time adjusted for timezone offset.
 */
function currentLocalDateObject() {
    const date = new Date();

    date.setMinutes(date.getMinutes() - date.getTimezoneOffset());

    return date;
}

/**
 * Returns the current local date and time formatted for <input type="datetime-local">.
 *
 * Output format: YYYY-MM-DDTHH:mm
 *
 * @returns {string} Local date/time string.
 */
function currentLocalDatetime() {
    const date = currentLocalDateObject();

    date.setSeconds(0, 0);

    return date.toISOString().slice(0, 16);
}

/**
 * Returns the current local date formatted for <input type="date">.
 *
 * Output format: YYYY-MM-DD
 *
 * @returns {string} Local date string.
 */
function currentLocalDate() {
    return currentLocalDateObject().toISOString().slice(0, 10);
}
