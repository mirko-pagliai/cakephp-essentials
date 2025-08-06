<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\TestSuite\Traits;

use Cake\Essentials\TestSuite\Traits\AssertQueryBindingsTrait;
use Cake\I18n\DateTime;
use Cake\ORM\Query\SelectQuery;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * AssertQueryBindingsTraitTest.
 *
 * This class extends `PHPUnit\Framework\TestCase`, to avoid conflicts.
 */
#[CoversTrait(AssertQueryBindingsTrait::class)]
class AssertQueryBindingsTraitTest extends TestCase
{
    protected static SelectQuery $Query;

    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        self::$Query = new class extends SelectQuery {
            public function __construct()
            {
            }
        };
        self::$Query->getValueBinder()->bind(':c0', 1, 'integer');
        self::$Query->getValueBinder()->bind(':c1', 'value', 'string');
    }

    /**
     * @link \Cake\Essentials\TestSuite\Traits\AssertQueryBindingsTrait::extractBindingValues()
     */
    #[Test]
    public function testExtractBindingValues(): void
    {
        $TestCase = new class ('MyTest') extends TestCase {
            use AssertQueryBindingsTrait {
                extractBindingValues as public;
            }
        };

        $Query = clone self::$Query;

        $DateTime = new DateTime('2025-06-12 14:30:00');
        $Query->getValueBinder()->bind(':c2', $DateTime, 'datetime');

        $expected = [
            ':c0' => 1,
            ':c1' => 'value',
            ':c2' => $DateTime,
        ];
        $result = $TestCase->extractBindingValues($Query);
        $this->assertSame($expected, $result);
    }

    /**
     * @link \Cake\Essentials\TestSuite\Traits\AssertQueryBindingsTrait::assertBindingsContains()
     */
    #[Test]
    public function testAssertBindingsContains(): void
    {
        $TestCase = new class ('MyTest') extends TestCase {
            use AssertQueryBindingsTrait;
        };

        $TestCase->assertBindingsContains([':c0' => 1], self::$Query);
        $TestCase->assertBindingsContains([':c1' => 'value'], self::$Query);

        // Simultaneous testing on both cases
        $TestCase->assertBindingsContains([':c0' => 1, ':c1' => 'value'], self::$Query);
    }

    /**
     * @link \Cake\Essentials\TestSuite\Traits\AssertQueryBindingsTrait::assertBindingsContains()
     */
    #[Test]
    public function testAssertBindingsContainsOnAssertionFailedCausedByKey(): void
    {
        $TestCase = new class ('MyTest') extends TestCase {
            use AssertQueryBindingsTrait;
        };

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that an array has the key \':c2\'.');
        $TestCase->assertBindingsContains([':c2' => 'badKey'], self::$Query);
    }

    /**
     * @link \Cake\Essentials\TestSuite\Traits\AssertQueryBindingsTrait::assertBindingsContains()
     */
    #[Test]
    public function testAssertBindingsContainsOnAssertionFailedCausedByValue(): void
    {
        $TestCase = new class ('MyTest') extends TestCase {
            use AssertQueryBindingsTrait;
        };

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that two strings are equal.');
        $TestCase->assertBindingsContains([':c1' => 'badValue'], self::$Query);
    }

    /**
     * @link \Cake\Essentials\TestSuite\Traits\AssertQueryBindingsTrait::assertBindingsEquals()
     */
    #[Test]
    public function testAssertBindingsEquals(): void
    {
        $TestCase = new class ('MyTest') extends TestCase {
            use AssertQueryBindingsTrait;
        };

        $Query = clone self::$Query;

        $DateTime = new DateTime('2025-06-12 14:30:00');
        $Query->getValueBinder()->bind(':c2', $DateTime, 'datetime');

        $expected = [
            ':c1' => 'value',
            ':c0' => 1,
            ':c2' => $DateTime,
        ];

        $TestCase->assertBindingsEquals($expected, $Query);
    }

    /**
     * @link \Cake\Essentials\TestSuite\Traits\AssertQueryBindingsTrait::assertBindingsEquals()
     */
    #[Test]
    public function testAssertBindingsEqualsOnAssertionFailed(): void
    {
        $TestCase = new class ('MyTest') extends TestCase {
            use AssertQueryBindingsTrait;
        };

        $this->expectException(AssertionFailedError::class);
        $TestCase->assertBindingsEquals([':c0' => 1], self::$Query);
    }

    /**
     * @link \Cake\Essentials\TestSuite\Traits\AssertQueryBindingsTrait::assertBindingsSame()
     */
    #[Test]
    public function testAssertBindingsSame(): void
    {
        $TestCase = new class ('MyTest') extends TestCase {
            use AssertQueryBindingsTrait;
        };

        $expected = [
            ':c0' => 1,
            ':c1' => 'value',
        ];

        $TestCase->assertBindingsSame($expected, self::$Query);
    }

    /**
     * @link \Cake\Essentials\TestSuite\Traits\AssertQueryBindingsTrait::assertBindingsSame()
     */
    #[Test]
    public function testAssertBindingsSameOnAssertionFailed(): void
    {
        $TestCase = new class ('MyTest') extends TestCase {
            use AssertQueryBindingsTrait;
        };

        $expected = [
            ':c1' => 'value',
            ':c0' => 1,
        ];

        $this->expectException(AssertionFailedError::class);
        $TestCase->assertBindingsSame($expected, self::$Query);
    }
}
