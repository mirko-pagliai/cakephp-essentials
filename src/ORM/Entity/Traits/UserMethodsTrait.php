<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Entity\Traits;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Essentials\ORM\Entity\EntityWithGetSetInterface;

/**
 * This trait implements some generic methods for the `User` entity.
 *
 * - `_setPassword()` ensures password hashing;
 * - `isId()` (and the `isFounder()` alias) and `isOwnerOf()` provide controls based on the user's ID;
 * - `isGroup()` (and the `isAdmin()` and `isManager()` aliases) provides checks based on the ID of the user group the
 *  user belongs to.
 *
 * @phpstan-require-implements \Cake\Essentials\ORM\Entity\UserInterface
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
        return in_array(needle: $this->getOrFail('id'), haystack: $id);
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
     * Returns `true` if the user belongs to the users group (even just one, if more than one has passed).
     *
     * @param string ...$name Group names
     * @return bool
     */
    public function isGroup(string ...$name): bool
    {
        /** @var \Cake\Essentials\ORM\Entity\EntityWithGetSetInterface $UsersGroup */
        $UsersGroup = $this->getOrFail('users_group');

        return in_array($UsersGroup->getOrFail('name'), $name);
    }

    /**
     * Returns `true` if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isGroup('admin');
    }

    /**
     * Returns `true` if the user is an admin or a manager.
     *
     * @return bool
     */
    public function isManager(): bool
    {
        return $this->isGroup('admin', 'manager');
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
        /** @var int $entityOwnerId */
        $entityOwnerId = $Entity->getOrFail('user_id');

        return $this->isId($entityOwnerId);
    }
}
