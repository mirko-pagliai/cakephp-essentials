<?php
declare(strict_types=1);

namespace Cake\Essentials\TestSuite\Traits;

use Cake\ORM\Query\SelectQuery;

/**
 * Provides utility methods for testing query bindings in select queries.
 */
trait AssertQueryBindingsTrait
{
    /**
     * Internal method to extract the binding values from the provided `SelectQuery` object.
     *
     * Example, from:
     * ```
     * [':c0' => ['value' => 1, 'type' => 'integer', 'placeholder' => 'c0']]
     * ```
     *
     * To:
     * ```
     * [':c0' => 1]
     * ```
     *
     * @param \Cake\ORM\Query\SelectQuery $Query The query object containing the value binder and its bindings.
     * @return array An array of binding values extracted from the query.
     */
    private function extractBindingValues(SelectQuery $Query): array
    {
        return array_map(
            callback: fn (array $v): mixed => $v['value'],
            array: $Query->getValueBinder()->bindings()
        );
    }

    /**
     * Asserts that the bindings of the given query match the expected values.
     *
     * @param array $expected The expected binding values.
     * @param \Cake\ORM\Query\SelectQuery $Query The query object whose bindings are being tested.
     * @return void
     */
    public function assertBindingsEquals(array $expected, SelectQuery $Query): void
    {
        $this->assertEquals($expected, $this->extractBindingValues($Query));
    }

    /**
     * Asserts that the bindings of the given query are the same as the expected values.
     *
     * @param array $expected The expected binding values.
     * @param \Cake\ORM\Query\SelectQuery $Query The query object whose bindings are being tested.
     * @return void
     */
    public function assertBindingsSame(array $expected, SelectQuery $Query): void
    {
        $this->assertSame($expected, $this->extractBindingValues($Query));
    }
}
