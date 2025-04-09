<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Entity\Traits;

/**
 * This trait implements some generic methods for the `User` entity.
 *
 * @psalm-require-extends \Cake\ORM\Entity
 * @psalm-require-implements \Authentication\IdentityInterface
 */
trait UserMethodsTrait
{
    /**
     * {@inheritDoc}
     *
     * @see https://book.cakephp.org/authentication/3/en/identity-object.html#implementing-the-identityinterface-on-your-user-class
     */
    public function getIdentifier(): int
    {
        return $this->getId();
    }

    /**
     * {@inheritDoc}
     *
     * @see https://book.cakephp.org/authentication/3/en/identity-object.html#implementing-the-identityinterface-on-your-user-class
     */
    public function getOriginalData(): self
    {
        return $this;
    }

    /**
     * Returns `true` if the user ID matches `$id` (even just one, if more than one has passed).
     *
     * This method can be useful to verify an identity in policies.
     *
     * For example:
     * ```
     * $Identity->isId(2)
     * ```
     *
     * @param int ...$id User ID you want to check
     * @return bool
     */
    public function isId(int ...$id): bool
    {
        return in_array(needle: $this->getId(), haystack: $id);
    }

    /**
     * Returns `true` if the user is the founder user.
     *
     * @return bool
     */
    public function isFounder(): bool
    {
        return $this->isId(1);
    }
}
