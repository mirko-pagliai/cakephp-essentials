<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Essentials\ORM\Entity\Traits\UserStatusMethodsTrait;
use Cake\Essentials\ORM\Traits\GetSetTrait;
use Cake\ORM\Entity;

/**
 * User Entity.
 *
 * @property \Cake\Essentials\ORM\Enum\UserStatus $status
 *
 * @method \Cake\Essentials\ORM\Enum\UserStatus getStatus()
 */
class User extends Entity
{
    use GetSetTrait;
    use UserStatusMethodsTrait;
}
