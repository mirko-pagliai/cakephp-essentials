<?php
declare(strict_types=1);

namespace Cake\Essentials\Database\Type;

use Cake\Database\Type\DateTimeType;
use DateTimeInterface;

/**
 * A custom DateTimeType that extends the base functionality to handle flexible input formats when marshaling data.
 */
class FlexibleDateTimeType extends DateTimeType
{
    /**
     * Converts a given value into a `DateTimeInterface` object or returns `null` if the value is not valid.
     *
     * @param mixed $value The value to be converted. Can be a string (e.g., ISO date format `YYYY-MM-DD`), a
     *  `DateTimeInterface` instance or `null`.
     * @return \DateTimeInterface|null Returns a `DateTimeInterface` object if the conversion is successful or `null`
     *  if the value is invalid or empty.
     */
    public function marshal(mixed $value): ?DateTimeInterface
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Se è stringa ISO solo data (YYYY-MM-DD)
        if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            $value .= ' 00:00:00';
        }

        return parent::marshal($value);
    }
}
