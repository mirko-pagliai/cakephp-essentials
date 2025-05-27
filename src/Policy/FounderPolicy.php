<?php
declare(strict_types=1);

namespace Cake\Essentials\Policy;

use Cake\Essentials\ORM\Entity\UserInterface;

/**
 * FounderPolicy.
 *
 * This abstract policy can be applied for an entity where only the fonder admin can perform any operation.
 */
abstract class FounderPolicy
{
    /**
     * Check if $Identity can add.
     *
     * @param \Cake\Essentials\ORM\Entity\UserInterface $Identity
     * @return bool
     */
    public function canAdd(UserInterface $Identity): bool
    {
        return $Identity->isFounder();
    }

    /**
     * Check if $Identity can edit.
     *
     * @param \Cake\Essentials\ORM\Entity\UserInterface $Identity
     * @return bool
     */
    public function canEdit(UserInterface $Identity): bool
    {
        return $Identity->isFounder();
    }

    /**
     * Check if $Identity can delete.
     *
     * @param \Cake\Essentials\ORM\Entity\UserInterface $Identity
     * @return bool
     */
    public function canDelete(UserInterface $Identity): bool
    {
        return $Identity->isFounder();
    }

    /**
     * Check if $Identity can view.
     *
     * @param \Cake\Essentials\ORM\Entity\UserInterface $Identity
     * @return bool
     */
    public function canView(UserInterface $Identity): bool
    {
        return $Identity->isFounder();
    }
}
