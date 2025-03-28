<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\TestSuite;

use Cake\Database\ValueBinder;
use Cake\Essentials\TestSuite\TestCase;
use Cake\I18n\DateTime;
use Cake\ORM\Query\SelectQuery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * TableTestCaseTest.
 *
 * This class, for testing, extends `PHPUnit\Framework\TestCase`, to avoid conflicts.
 */
#[CoversClass(TestCase::class)]
class TestCaseTest extends PHPUnitTestCase
{
    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    public function testGetValuesFromValueBinder(): void
    {
        $DateTime = new DateTime('2025-03-27 16:49:04');

        $ValueBinder = new ValueBinder();
        $ValueBinder->bind(':c0', 'value0', 'string');
        $ValueBinder->bind(':c1', 1, 'int');
        $ValueBinder->bind(':c2', true, 'boolean');
        $ValueBinder->bind(':c3', $DateTime, 'datetime');

        $SelectQuery = $this->createConfiguredMock(SelectQuery::class, ['getValueBinder' => $ValueBinder]);

        $expected = [
            ':c0' => 'value0',
            ':c1' => 1,
            ':c2' => true,
            ':c3' => (string)$DateTime,
        ];
        $TableTestCase = new TestCase('Test');
        $this->assertSame($expected, $TableTestCase->getValuesFromValueBinder($SelectQuery));
    }
}
