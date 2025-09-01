<?php
declare(strict_types=1);

namespace Cake\Essentials\Http\Exception;

use Cake\Http\Exception\ForbiddenException;
use Throwable;
use function Cake\I18n\__d as __d;

/**
 * InvalidTokenException.
 *
 * This is a specific exception thrown when a user is trying to use an invalid or expired token.
 *
 * Represents an HTTP 403 error.
 *
 * @see https://github.com/dereuromark/cakephp-tools/blob/master/docs/Model/Tokens.md
 */
class InvalidTokenException extends ForbiddenException
{
    /**
     * @inheritDoc
     */
    public function __construct(?string $message = null, ?int $code = null, ?Throwable $previous = null)
    {
        parent::__construct(
            $message ?: __d('cake/essentials', 'Invalid token or request expired'),
            $code,
            $previous,
        );
    }
}
