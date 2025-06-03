<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface;
use Cake\Datasource\EntityInterface;

/**
 * An example policy, for tests.
 */
class ExamplePolicy
{
    public function canAdd(IdentityInterface&EntityInterface $Identity): bool
    {
        return $Identity->get('id') === 1;
    }

    public function canEdit(IdentityInterface&EntityInterface $Identity, EntityInterface $Entity): bool
    {
        return $Identity->get('id') === 2 && $Entity->get('status') == true;
    }
}
