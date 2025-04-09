<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Entity\Traits;

/**
 * This trait implements some methods for the `User` entity and that concern the user group of a user.
 *
 * Expected that the `User` entity has the `users_group` property and that this has the `name` property, it implements
 *  the `isGroup()` method and some other methods which are quick aliases of it.
 *
 * @psalm-require-extends \Cake\ORM\Entity
 */
trait UsersGroupMethodsTrait
{
    /**
     * Returns `true` if the user belongs to the group or (if more than one group is specified) to at least one of the
     *  groups.
     *
     * @param string ...$name Group names
     * @return bool
     */
    public function isGroup(string ...$name): bool
    {
        return in_array($this->getUsersGroup()->getName(), $name);
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
}
