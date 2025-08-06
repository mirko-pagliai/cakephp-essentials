<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase;

use Cake\I18n\Date;
use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use DateMalformedStringException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use function Cake\Essentials\toDate;
use function Cake\Essentials\toDatetime;

/**
 * GlobalFunctionsTest.
 *
 * @link src/global_functions.php
 */
class GlobalFunctionsTest extends TestCase
{
    #[Test]
    #[TestWith(['today'])]
    #[TestWith([new Date('today')])]
    public function testToDate(string|Date $date): void
    {
        $result = toDate($date);
        $this->assertEquals(Date::today(), $result);
    }

    #[Test]
    public function testToDateWithMalformedString(): void
    {
        $this->expectException(DateMalformedStringException::class);
        toDate('malformedString');
    }

    #[Test]
    #[TestWith(['2025-06-25 14:00:00'])]
    #[TestWith([new Datetime('2025-06-25 14:00:00')])]
    public function testToDatetime(string|DateTime $datetime): void
    {
        $result = toDatetime($datetime);
        $this->assertEquals(new DateTime('2025-06-25 14:00:00'), $result);
    }

    #[Test]
    public function testToDatetimeWithMalformedString(): void
    {
        $this->expectException(DateMalformedStringException::class);
        toDateTime('malformedString');
    }
}
