<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Entity\Traits;

use Cake\Essentials\ORM\Enum\UserStatus;

/**
 * UserStatusMethodsTrait.
 *
 * Provides some methods for the `User` entity based on the `status` field and hence the `UserStatus` enum.
 *
 * @psalm-require-extends \Cake\ORM\Entity
 *
 * @see \Cake\Essentials\ORM\Enum\UserStatus
 */
trait UserStatusMethodsTrait
{
    /**
     * Returns `true` if the user is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->getStatus() == UserStatus::Active;
    }

    /**
     * Returns `true` if the user is disabled.
     *
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->getStatus() == UserStatus::Disabled;
    }

    /**
     * Returns `true` if the user is pending.
     *
     * This matches with (and functions as an alias for) `RequiresAdminActivation` or `RequiresUserActivation` case.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->requiresAdminActivation() || $this->requiresUserActivation();
    }

    /**
     * Returns `true` if the user account requires administrator activation.
     *
     * @return bool
     */
    public function requiresAdminActivation(): bool
    {
        return $this->getStatus() == UserStatus::RequiresAdminActivation;
    }

    /**
     * Returns `true` if the user account requires user activation.
     *
     * @return bool
     */
    public function requiresUserActivation(): bool
    {
        return $this->getStatus() == UserStatus::RequiresUserActivation;
    }
}
