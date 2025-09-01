<?php
declare(strict_types=1);

namespace Cake\Essentials\Http\Exception;

use Cake\Http\Exception\ForbiddenException;
use Throwable;
use function Cake\I18n\__d as __d;

/**
 * IpAddressNotAllowedException.
 *
 * This is a specific exception thrown when an IP address tries to perform a restricted operation.
 *
 *  Represents an HTTP 403 error.
 */
class IpAddressNotAllowedException extends ForbiddenException
{
    /**
     * @inheritDoc
     */
    public function __construct(?string $message = null, ?int $code = null, ?Throwable $previous = null)
    {
        parent::__construct(
            $message ?: __d('cake/essentials', 'IP address not allowed for this operation'),
            $code,
            $previous,
        );
    }
}
