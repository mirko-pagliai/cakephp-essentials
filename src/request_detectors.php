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
 * $this->getRequest()->is('action', 'delete')
 * </code>
 * returns `true` if the current action is `delete`, otherwise `false`.
 *
 * Example:
 * <code>
 * $this->getRequest()->isAction('action', 'edit', 'delete')
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
 * These are quick aliases for `is('action')` detectors.
 *
 * Example:
 * <code>
 * $this->getRequest()->is('delete')
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
 * This is a quick alias for `is('ip')` detector.
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
 *
 * Example:
 * <code>
 * $this->getRequest()->is('ip', '99.99.99.99')
 * </code>
 * returns `true` if the current client IP is `99.99.99.99`, otherwise `false`.
 *
 * Example:
 * <code>
 * $this->getRequest()->isAction('ip', ['99.99.99.99', '11.11.11.11'])
 * </code>
 * returns `true` if the current client IP is `99.99.99.99` or `11.11.11.11`, otherwise `false`.
 */
ServerRequest::addDetector(
    name: 'ip',
    detector: fn(ServerRequest $Request, string ...$ip): bool => in_array(needle: $Request->clientIp(), haystack: $ip),
);

/**
 * `is('trustedClient')` detector.
 *
 * This is a quick alias for `is('ip')` detector.
 *
 * Returns `true` if it is a trusted client.
 *
 * Before using this detector, you should write trusted clients into the configuration.
 *
 * For example, in your `bootstrap.php` file,
 * <code>
 * Configure::write('trustedIpAddress', ['45.46.47.48', '192.168.0.100']);
 * </code>
 *
 * At this point,
 * <code>
 * $this->getRequest()->isAction('trustedClient')
 * </code>
 * returns `true` if the current client IP matches one of these.
 */
ServerRequest::addDetector(name: 'trustedClient', detector: function (ServerRequest $Request): bool {
    if ($Request->is('localhost')) {
        return true;
    }

    return $Request->is('ip', ...(array)Configure::readOrFail('trustedIpAddress'));
});
