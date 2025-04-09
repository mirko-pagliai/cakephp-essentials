<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Entity\Traits;

use BadMethodCallException;
use Cake\Datasource\Exception\MissingPropertyException;
use Cake\Utility\Inflector;

/**
 * This trait implements `getOrFail()`, `isOrFail()` and all `getX()` and `isX()` magic method.
 *
 * These methods allow you to return the value of the entity's properties, throwing an exception when the property does
 *  not exist or is `null`.
 *
 * @psalm-require-implements \Cake\Essentials\ORM\Entity\EntityWithGetSetInterface
 */
trait GetSetTrait
{
    /**
     * Magic `__call()` method.
     *
     * Allows you to magically implement `getX()` and `isX()` methods for entities.
     *
     * For example, the `getCreated()` method will return the value of the `created` property.
     * Instead, the `isActive()` method returns a boolean.
     *
     * These methods will explicitly call `getOrFail()`, then an exception will be thrown if the property does not
     *  exist or is `null`.
     * So, if you want an entity's `getX()` or `isX()` method to have a different behavior, you must explicitly declare
     *  it in its class.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \BadMethodCallException With a no existing method
     * @throws \Cake\Datasource\Exception\MissingPropertyException When the property does not exist or is `null`
     */
    public function __call(string $name, array $arguments): mixed
    {
        if (str_starts_with($name, 'get')) {
            return $this->getOrFail(property: Inflector::underscore(substr(string: $name, offset: 3)));
        } elseif (str_starts_with($name, 'is')) {
            return $this->isOrFail(property: Inflector::underscore(substr(string: $name, offset: 2)));
        }

        throw new BadMethodCallException(sprintf('Method `%s::%s()` does not exist. `get{PropertyName}()`/`is{PropertyName}()` expected.', $this::class, $name));
    }

    /**
     * Returns the value of a field by name.
     *
     * Unlike the `get()` method, it throws an exception if the property does not exist or is `null`.
     *
     * @param string $property Property name
     * @return mixed
     * @throws \Cake\Datasource\Exception\MissingPropertyException When the property does not exist or is `null`
     */
    public function getOrFail(string $property): mixed
    {
        if (!isset($this->{$property})) {
            throw new MissingPropertyException(['property' => $property, 'entity' => $this::class]);
        }

        return $this->{$property};
    }

    /**
     * Returns the value of a field by name as boolean.
     *
     * @param string $property Property name
     * @return bool
     * @throws \Cake\Datasource\Exception\MissingPropertyException When the property does not exist or is `null`
     */
    public function isOrFail(string $property): bool
    {
        return (bool)$this->getOrFail($property);
    }
}
