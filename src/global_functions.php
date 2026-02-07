<?php
declare(strict_types=1);

namespace Cake\Essentials;

use Cake\Chronos\ChronosDate;
use Cake\Chronos\ChronosTime;
use Cake\I18n\Date;
use Cake\I18n\DateTime;
use DateTimeInterface;
use InvalidArgumentException;

if (!function_exists('Cake\Essentials\rtr')) {
    /**
     * Generates a relative path from the given absolute path with respect to the application root.
     *
     * For example,
     * <code>
     * rtr(ROOT . 'webroot/assets')
     * </code>
     * returns `webroot/assets`.
     *
     * @param string $path The absolute path to be converted. It must start with the application's root directory.
     * @return string The relative path corresponding to the provided absolute path. If the input path ends with a
     *  directory separator, the returned relative path will also end with a directory separator.
     * @throws \InvalidArgumentException If the given path does not start with the application root directory.
     */
    function rtr(string $path): string
    {
        $root = rtrim(ROOT, DS) . DS;
        if (!str_starts_with($path, $root)) {
            throw new InvalidArgumentException("Path `$path` does not start with the application root");
        }

        return substr($path, strlen($root));
    }
}

if (!function_exists('Cake\Essentials\toDate')) {
    /**
     * Converts the given input into a Date object.
     *
     * @param \Cake\Chronos\ChronosDate|\DateTimeInterface|string $date The input value to be converted. It can be an
     *  instance of `Date` or a fixed or relative time.
     * @return \Cake\I18n\Date The converted `Date` object.
     */
    function toDate(ChronosDate|DateTimeInterface|string $date): Date
    {
        return $date instanceof Date ? $date : new Date($date);
    }
}

if (!function_exists('Cake\Essentials\toDateTime')) {
    /**
     * Converts the given input into a DateTime object.
     *
     * @param \Cake\Chronos\ChronosDate|\Cake\Chronos\ChronosTime|\DateTimeInterface|string|int|null $dateTime The input
     *  value to be converted. It can be an instance of `DateTime` or a fixed or relative time.
     * @return \Cake\I18n\DateTime The converted `DateTime` object.
     */
    function toDateTime(ChronosDate|ChronosTime|DateTimeInterface|string|int|null $dateTime): DateTime
    {
        return $dateTime instanceof DateTime ? $dateTime : new DateTime($dateTime);
    }
}
