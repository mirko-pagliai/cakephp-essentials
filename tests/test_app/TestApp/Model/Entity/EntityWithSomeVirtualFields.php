<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Essentials\ORM\Entity\EntityWithGetSetInterface;
use Cake\Essentials\ORM\Entity\Traits\GetSetTrait;
use Cake\ORM\Entity;

/**
 * EntityWithSomeVirtualFields.
 */
class EntityWithSomeVirtualFields extends Entity implements EntityWithGetSetInterface
{
    use GetSetTrait;

    protected function _getStringableVirtualField(): string
    {
        return 'a string';
    }

    protected function _getNullableVirtualField(): null
    {
        return null;
    }
}
