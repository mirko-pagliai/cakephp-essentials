<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Entity\Traits;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Essentials\ORM\Entity\EntityWithGetSetInterface;

/**
 * This trait implements some generic methods for the `User` entity.
 *
 * @psalm-require-extends \Cake\ORM\Entity
 * @psalm-require-implements \Authentication\IdentityInterface
 * @psalm-require-implements \Cake\Essentials\ORM\Entity\EntityWithGetSetInterface
 */
trait UserMethodsTrait
{
    /**
     * Password hasher.
     *
     * @param string $password
     * @return string
     *
     * @see https://book.cakephp.org/authentication/3/en/index.html#adding-password-hashing
     */
    protected function _setPassword(string $password): string
    {
        return new DefaultPasswordHasher()->hash($password);
    }

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

    /**
     * Returns `true` if the user is the owner of `$Entity`.
     *
     * `$Entity` must have the `user_id` field.
     *
     * @param \Cake\Essentials\ORM\Entity\EntityWithGetSetInterface $Entity
     * @return bool
     */
    public function isOwnerOf(EntityWithGetSetInterface $Entity): bool
    {
        /** @var int $ownerId */
        $ownerId = $Entity->getOrFail('user_id');

        return $this->isId($ownerId);
    }
}
