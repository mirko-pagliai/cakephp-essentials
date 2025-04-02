<?php
declare(strict_types=1);

use Cake\Http\ServerRequest;

/**
 * `is('action')` detector.
 *
 * Checks if `$action` matches the current action.
 *
 * The `$action` argument can be a string or an array of strings. In the second case, it is sufficient that the action
 * matches one of those.
 *
 * Example:
 * <code>
 * $this->getRequest()->is('action', 'delete');
 * </code>
 * returns `true` if the current action is `delete`, otherwise `false`.
 *
 * Example:
 * <code>
 * $this->getRequest()->isAction('action', 'edit', 'delete');
 * </code>
 * returns `true` if the current action is `edit` or `delete`, otherwise `false`.
 */
ServerRequest::addDetector(
    name: 'action',
    detector: fn (ServerRequest $Request, string ...$action): bool => in_array(needle: $Request->getParam('action'), haystack: $action)
);

/**
 * Other "action detectors": `is('add')`, `is('edit')`, `is('view')`, `is('index')`, `is('delete')`.
 *
 * They are quick aliases for `is('action')` detectors.
 *
 * Example:
 * <code>
 * $this->getRequest()->is('delete');
 * </code>
 * returns `true` if the current action is `delete`, otherwise `false`.
 */
foreach (['add', 'delete', 'edit', 'index', 'view'] as $action) {
    ServerRequest::addDetector($action, fn (ServerRequest $Request): bool => $Request->is(type: 'action', args: $action));
}

/**
 * `is('localhost')` detector.
 *
 * Returns `true` if the current client IP matches localhost.
 */
ServerRequest::addDetector(
    name: 'localhost',
    detector: fn (ServerRequest $Request): bool => $Request->is('ip', '127.0.0.1', '::1')
);

/**
 * `is('ip')` detector.
 *
 * Checks whether the current client IP matches the IP or one of the IPs passed as an argument.
 */
ServerRequest::addDetector(
    name: 'ip',
    detector: fn (ServerRequest $Request, string ...$ip): bool => in_array(needle: $Request->clientIp(), haystack: $ip)
);
