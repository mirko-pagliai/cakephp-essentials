<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Cake\Http\ServerRequest;

/**
 * `is('action')` detector.
 *
 * Checks if `$action` matches the current action.
 *
 * The `$action` argument can be a string or an array of strings. In the second case, it is enough that the action
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
    detector: fn(ServerRequest $Request, string ...$action): bool => in_array(
        needle: $Request->getParam('action'),
        haystack: $action,
    ),
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
ServerRequest::addDetector('add', fn(ServerRequest $Request): bool => $Request->is(type: 'action', args: 'add'));
ServerRequest::addDetector('delete', fn(ServerRequest $Request): bool => $Request->is(type: 'action', args: 'delete'));
ServerRequest::addDetector('edit', fn(ServerRequest $Request): bool => $Request->is(type: 'action', args: 'edit'));
ServerRequest::addDetector('index', fn(ServerRequest $Request): bool => $Request->is(type: 'action', args: 'index'));
ServerRequest::addDetector('view', fn(ServerRequest $Request): bool => $Request->is(type: 'action', args: 'view'));

/**
 * `is('localhost')` detector.
 *
 * Returns `true` if the current client IP matches localhost.
 */
ServerRequest::addDetector(
    name: 'localhost',
    detector: fn(ServerRequest $Request): bool => $Request->is('ip', '127.0.0.1', '::1'),
);

/**
 * `is('ip')` detector.
 *
 * Checks whether the current client IP matches the IP or one of the IPs passed as an argument.
 */
ServerRequest::addDetector(
    name: 'ip',
    detector: fn(ServerRequest $Request, string ...$ip): bool => in_array(needle: $Request->clientIp(), haystack: $ip),
);

/**
 * `is('trustedClient')` detector.
 *
 * Returns `true` if it is a trusted client.
 *
 * Before using this detector, you should write trusted clients into the configuration, for example:
 * ```
 * Configure::write('trustedIpAddress', ['45.46.47.48', '192.168.0.100']);
 * ```
 */
ServerRequest::addDetector(name: 'trustedClient', detector: function (ServerRequest $Request): bool {
    if ($Request->is('localhost')) {
        return true;
    }

    return $Request->is('ip', ...(array)Configure::readOrFail('trustedIpAddress'));
});
