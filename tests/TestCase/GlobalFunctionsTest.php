<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase;

use Cake\I18n\Date;
use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use DateMalformedStringException;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\RequiresOperatingSystemFamily;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use function Cake\Essentials\rtr;
use function Cake\Essentials\toDate;
use function Cake\Essentials\toDatetime;

/**
 * GlobalFunctionsTest.
 *
 * @link src/global_functions.php
 */
class GlobalFunctionsTest extends TestCase
{
    /**
     * @link \Cake\Essentials\rtr()
     */
    #[Test]
    #[TestWith([ROOT . 'webroot', 'webroot'])]
    #[TestWith([ROOT . 'webroot/assets', 'webroot/assets'])]
    #[TestWith([ROOT . DS . 'webroot', 'webroot'])]
    #[TestWith([ROOT . DS . 'webroot' . DS, 'webroot' . DS])]
    #[RequiresOperatingSystemFamily('Linux')]
    public function testRtr(string $path, string $expectedRelativePath): void
    {
        $result = rtr($path);
        $this->assertEquals($expectedRelativePath, $result);
    }

    /**
     * @link \Cake\Essentials\rtr()
     */
    #[Test]
    public function testRtrWithPathDoesNotStartWithRoot(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Path `/some/path` does not start with the application root');
        rtr('/some/path');
    }

    /**
     * @link \Cake\Essentials\rtr()
     */
    #[Test]
    public function testRtrWithNotAbsolutePath(): void
    {
        $this->expectException(InvalidArgumentException::class);
        rtr('malformedPath');
    }

    /**
     * @link \Cake\Essentials\toDate()
     */
    #[Test]
    #[TestWith(['today'])]
    #[TestWith([new Date('today')])]
    #[TestWith([new DateTimeImmutable('today')])]
    public function testToDate(Date|DateTimeImmutable|string $date): void
    {
        $result = toDate($date);
        $this->assertEquals(Date::today(), $result);
    }

    /**
     * @link \Cake\Essentials\toDate()
     */
    #[Test]
    public function testToDateWithMalformedString(): void
    {
        $this->expectException(DateMalformedStringException::class);
        toDate('malformedString');
    }

    /**
     * @link \Cake\Essentials\toDateTime()
     */
    #[Test]
    #[TestWith(['2025-06-25 14:00:00'])]
    #[TestWith([new Datetime('2025-06-25 14:00:00')])]
    #[TestWith([new DateTimeImmutable('2025-06-25 14:00:00')])]
    #[TestWith([1750860000])]
    public function testToDatetime(DateTime|DateTimeImmutable|string|int $datetime): void
    {
        $result = toDatetime($datetime);
        $this->assertEquals(new DateTime('2025-06-25 14:00:00'), $result);
    }

    /**
     * @link \Cake\Essentials\toDateTime()
     */
    #[Test]
    public function testToDatetimeWithMalformedString(): void
    {
        $this->expectException(DateMalformedStringException::class);
        toDateTime('malformedString');
    }
}
