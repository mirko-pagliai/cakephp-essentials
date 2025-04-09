<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Essentials\ORM\Entity\EntityWithGetSetInterface;
use Cake\Essentials\ORM\Entity\Traits\GetSetTrait;
use Cake\ORM\Entity;

/**
 * UsersGroup.
 *
 * @property string $name
 *
 * @method string getName()
 */
class UsersGroup extends Entity implements EntityWithGetSetInterface
{
    use GetSetTrait;
}
