<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase;

use Cake\I18n\Date;
use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use DateMalformedStringException;
use DateTimeImmutable;
use InvalidArgumentException;
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
     * Tests that including the file again does not redeclare its functions.
     */
    #[Test]
    public function testIncludeAgainDoesNotRedeclareFunctions(): void
    {
        /**
         * The `function_exists()` checks run only when the file is loaded, so the file is included again to run them
         * while the code coverage is being collected
         */
        $result = include ROOT . 'src' . DS . 'global_functions.php';
        $this->assertSame(1, $result);
    }

    /**
     * @link \Cake\Essentials\rtr()
     */
    #[Test]
    #[TestWith([ROOT . 'webroot', 'webroot'])]
    #[TestWith([ROOT . 'webroot/assets', 'webroot/assets'])]
    #[TestWith([ROOT . 'webroot' . DS, 'webroot' . DS])]
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
        $this->expectExceptionMessageIs('Path `/some/path` does not start with the application root');
        rtr('/some/path');
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
        $expected = new Date('today');
        $result = toDate($date);
        $this->assertEquals($expected, $result);
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
        $expected = new DateTime('2025-06-25 14:00:00');
        $result = toDatetime($datetime);
        $this->assertEquals($expected, $result);
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
