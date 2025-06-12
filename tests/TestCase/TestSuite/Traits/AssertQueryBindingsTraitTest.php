<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\TestSuite\Traits;

use Cake\Essentials\TestSuite\Traits\AssertQueryBindingsTrait;
use Cake\I18n\DateTime;
use Cake\ORM\Query\SelectQuery;
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
    #[Test]
    public function testExtractBindingValues(): void
    {
        $TestCase = new class ('Test') extends TestCase {
            use AssertQueryBindingsTrait {
                extractBindingValues as public;
            }
        };

        $DateTime = new DateTime('2025-06-12 14:30:00');

        $Query = new class extends SelectQuery {
            public function __construct()
            {
            }
        };
        $Query->getValueBinder()->bind(':c0', 1, 'integer');
        $Query->getValueBinder()->bind(':c1', 'value', 'string');
        $Query->getValueBinder()->bind(':c2', $DateTime, 'datetime');

        $expected = [
            ':c0' => 1,
            ':c1' => 'value',
            ':c2' => $DateTime,
        ];
        $result = $TestCase->extractBindingValues($Query);
        $this->assertSame($expected, $result);
    }
}
