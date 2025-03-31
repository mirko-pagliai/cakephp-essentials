<?php
declare(strict_types=1);

use Cake\Http\ServerRequest;

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
    detector: function (ServerRequest $Request, string ...$ip): bool {
        return in_array(needle: $Request->clientIp(), haystack: $ip);
    }
);
