<?php
declare(strict_types=1);

namespace Cake\Essentials\Log;

use Cake\Log\LogTrait as CakeLogTrait;
use Cake\Routing\Router;
use Psr\Log\LogLevel;
use Stringable;

/**
 * Provides the ability to log messages with additional context such as client IP.
 *
 * This trait uses `\Cake\Log\LogTrait` to handle the underlying logging functionality,
 * while enhancing log messages by optionally prepending the client IP address when available.
 */
trait LogTrait
{
    use CakeLogTrait {
        log as private _log;
    }

    /**
     * Logs a message with a specified level and context.
     *
     * @param \Stringable|string $message The message to log. If the request has a client IP, the message is prefixed with the IP.
     * @param string|int $level The log level. Defaults to LogLevel::ERROR.
     * @param array<string, mixed>|string $context Additional context for the log message.
     * @return bool Returns true on successful logging, false otherwise.
     */
    public function log(Stringable|string $message, string|int $level = LogLevel::ERROR, array|string $context = []): bool
    {
        /**
         * @var \Cake\Http\ServerRequest $request
         * @phpstan-ignore function.alreadyNarrowedType, function.impossibleType
         */
        $request = method_exists($this, 'getRequest') ? $this->getRequest() : Router::getRequest();

        return $this->_log(
            message: $request->clientIp() ? $request->clientIp() . ' - ' . $message : $message,
            level: $level,
            context: $context,
        );
    }
}
