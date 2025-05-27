<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Entity;

/**
 * This interface can be implemented by a `User` entity.
 *
 * The fastest and most effective strategy to implement these methods is to use the `UserMethodsTrait` trait.
 *
 * @see \Cake\Essentials\ORM\Entity\Traits\UserMethodsTrait
 */
interface UserInterface extends EntityWithGetSetInterface
{
    /**
     * Returns `true` if the user ID matches `$id` (even just one, if more than one has passed).
     *
     * @param int ...$id User ID you want to check
     * @return bool
     */
    public function isId(int ...$id): bool;

    /**
     * Returns `true` if the user is the founder user.
     *
     * @return bool
     */
    public function isFounder(): bool;

    /**
     * Returns `true` if the user belongs to the users group (even just one, if more than one has passed).
     *
     * @param string ...$name Group names
     * @return bool
     */
    public function isGroup(string ...$name): bool;

    /**
     * Returns `true` if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool;

    /**
     * Returns `true` if the user is an admin or a manager.
     *
     * @return bool
     */
    public function isManager(): bool;

    /**
     * Returns `true` if the user is the owner of `$Entity`.
     *
     * `$Entity` must have the `user_id` field.
     *
     * @param \Cake\Essentials\ORM\Entity\EntityWithGetSetInterface $Entity
     * @return bool
     */
    public function isOwnerOf(EntityWithGetSetInterface $Entity): bool;
}
