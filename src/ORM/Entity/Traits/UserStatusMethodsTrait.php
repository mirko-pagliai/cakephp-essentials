<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Entity\Traits;

use Cake\Essentials\ORM\Enum\UserStatus;

/**
 * UserStatusMethodsTrait.
 *
 * Provides some methods for the `User` entity based on the `status` field and hence the `UserStatus` enum.
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
     * This matches `RequiresAdminActivation` and `RequiresUserActivation` cases.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return in_array(needle: $this->getStatus(), haystack: [UserStatus::RequiresAdminActivation, UserStatus::RequiresUserActivation]);
    }
}
