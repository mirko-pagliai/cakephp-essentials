<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Entity\Traits;

/**
 * This trait, expecting an entity to naturally provide `last_name` and `first_name` fields, implements the `full_name`
 *  virtual field and the `getFullName()` method.
 *
 * @property-read non-empty-string|null $full_name
 *
 * @psalm-require-implements \Cake\Essentials\ORM\Entity\EntityWithGetSetInterface
 */
trait GetFullNameTrait
{
    /**
     * Provides the `full_name` virtual field.
     *
     * @return non-empty-string|null
     */
    protected function _getFullName(): ?string
    {
        $lastName = $this->get('last_name');
        $firstName = $this->get('first_name');

        // @phpstan-ignore-next-line
        return $lastName && $firstName ? $lastName . ' ' . $firstName : null;
    }

    /**
     * Returns the full name.
     *
     * @return non-empty-string
     * @throws \Cake\Datasource\Exception\MissingPropertyException
     */
    public function getFullName(): string
    {
        // @phpstan-ignore-next-line
        return $this->getOrFail('last_name') . ' ' . $this->getOrFail('first_name');
    }
}
