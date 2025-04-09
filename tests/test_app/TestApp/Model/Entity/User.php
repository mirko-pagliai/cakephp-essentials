<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Essentials\ORM\Entity\Traits\GetSetTrait;
use Cake\Essentials\ORM\Entity\Traits\UsersGroupMethodsTrait;
use Cake\Essentials\ORM\Entity\Traits\UserStatusMethodsTrait;
use Cake\ORM\Entity;

/**
 * User Entity.
 *
 * @property \Cake\Essentials\ORM\Enum\UserStatus $status
 * @property \App\Model\Entity\UsersGroup $users_group
 *
 * @method \Cake\Essentials\ORM\Enum\UserStatus getStatus()
 * @method \App\Model\Entity\UsersGroup getUsersGroup()
 */
class User extends Entity
{
    use GetSetTrait;
    use UsersGroupMethodsTrait;
    use UserStatusMethodsTrait;
}
