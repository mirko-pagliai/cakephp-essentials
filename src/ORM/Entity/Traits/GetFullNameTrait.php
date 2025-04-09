<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Entity\Traits;

/**
 * This trait, expecting an entity to naturally provide `last_name` and `first_name` fields, implements the `full_name`
 *  virtual field and the `getFullName()` method.
 *
 * @property-read string|null $full_name
 *
 * @psalm-require-implements \Cake\Essentials\ORM\Entity\EntityWithGetSetInterface
 */
trait GetFullNameTrait
{
    /**
     * Provides the `full_name` virtual field.
     *
     * @return string|null
     */
    protected function _getFullName(): ?string
    {
        /** @var string|null $lastName */
        $lastName = $this->get('last_name');
        /** @var string|null $firstName */
        $firstName = $this->get('first_name');

        return $lastName && $firstName ? $lastName . ' ' . $firstName : null;
    }

    /**
     * Returns the full name.
     *
     * @return string
     * @throws \Cake\Datasource\Exception\MissingPropertyException
     */
    public function getFullName(): string
    {
        /** @var string $lastName */
        $lastName = $this->getOrFail('last_name');
        /** @var string $firstName */
        $firstName = $this->getOrFail('first_name');

        return $lastName . ' ' . $firstName;
    }
}
