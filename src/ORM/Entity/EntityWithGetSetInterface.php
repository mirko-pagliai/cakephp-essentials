<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Entity;

/**
 * This interface can be implemented by entities that want to provide `getOrFail()` and `isOrFail()` methods
 *
 * These methods allow you to return the value of the entity's properties, throwing an exception when the property does
 *  not exist or is `null`.
 *
 * The fastest and most effective strategy to implement these methods is to use the `GetSetTrait` trait.
 *
 * @phpstan-require-extends \Cake\ORM\Entity
 *
 * @see \Cake\Essentials\ORM\Entity\Traits\GetSetTrait
 */
interface EntityWithGetSetInterface
{
    /**
     * Returns the value of a field by name.
     *
     * Unlike the `get()` method, it throws an exception if the property does not exist or is `null`.
     *
     * @param string $property Property name
     * @return mixed
     * @throws \Cake\Datasource\Exception\MissingPropertyException When the property does not exist or is `null`
     */
    public function getOrFail(string $property): mixed;

    /**
     * Returns the value of a field by name as boolean.
     *
     * @param string $property Property name
     * @return bool
     * @throws \Cake\Datasource\Exception\MissingPropertyException When the property does not exist or is `null`
     */
    public function isOrFail(string $property): bool;
}
