<?php
declare(strict_types=1);

namespace Cake\Essentials\Utility;

use Cake\Utility\Text as CakeText;

/**
 * @inheritDoc
 */
class Text extends CakeText
{
    /**
     * Masks the characters of an email address, replacing all but the first character, domain, and special characters
     * with asterisks.
     *
     * @param string $email The email address to be masked.
     * @return string The masked email address.
     */
    public static function maskEmail(string $email): string
    {
        return preg_replace('/\B[^@.]/', '*', $email) ?: $email;
    }
}
