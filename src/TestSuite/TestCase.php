<?php
declare(strict_types=1);

namespace Cake\Essentials\TestSuite;

use Cake\ORM\Query\SelectQuery;
use Cake\TestSuite\TestCase as CakeTestCase;
use Stringable;

/**
 * TestCase class.
 *
 * This class adds some useful methods for testing.
 */
class TestCase extends CakeTestCase
{
    /**
     * Extracts and gets the values from the `ValueBinder`.
     *
     * Where possible, values will be converted to strings (e.g. for `Date` or `Datetime` instances).
     *
     * @param \Cake\ORM\Query\SelectQuery $Query
     * @return array<string, mixed>
     */
    public function getValuesFromValueBinder(SelectQuery $Query): array
    {
        return array_map(
            callback: fn (array $v): mixed => $v['value'] instanceof Stringable ? (string)$v['value'] : $v['value'],
            array: $Query->getValueBinder()->bindings()
        );
    }
}
