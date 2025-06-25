<?php
declare(strict_types=1);

namespace Cake\Essentials;

use Cake\I18n\Date;
use Cake\I18n\DateTime;

if (!function_exists('\Cake\Essentials\toDate')) {
    /**
     * Converts the given input into a Date object.
     *
     * @param \Cake\I18n\Date|string $date The input value to be converted. It can either be an instance of Date or a
     *  string representing a date.
     * @return \Cake\I18n\Date The converted Date object.
     */
    function toDate(Date|string $date): Date
    {
        return $date instanceof Date ? $date : new Date($date);
    }
}

if (!function_exists('\Cake\Essentials\toDateTime')) {
    /**
     * Converts the given input into a DateTime object.
     *
     * @param \Cake\I18n\DateTime|string $dateTime The input value to be converted. It can either be an instance of
     *  DateTime or a string representing a date.
     * @return \Cake\I18n\DateTime The converted DateTime object.
     */
    function toDateTime(DateTime|string $dateTime): DateTime
    {
        return $dateTime instanceof DateTime ? $dateTime : new DateTime($dateTime);
    }
}
