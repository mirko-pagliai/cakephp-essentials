<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\IdentityInterface;
use Cake\Essentials\ORM\Entity\EntityWithGetSetInterface;
use Cake\Essentials\ORM\Entity\Traits\GetSetTrait;
use Cake\Essentials\ORM\Entity\Traits\UserMethodsTrait;
use Cake\Essentials\ORM\Entity\Traits\UsersGroupMethodsTrait;
use Cake\Essentials\ORM\Entity\Traits\UserStatusMethodsTrait;
use Cake\ORM\Entity;

/**
 * User Entity.
 *
 * @property int $id
 * @property string $password
 * @property \Cake\Essentials\ORM\Enum\UserStatus $status
 * @property \App\Model\Entity\UsersGroup $users_group
 *
 * @method int getId()
 * @method \Cake\Essentials\ORM\Enum\UserStatus getStatus()
 * @method \App\Model\Entity\UsersGroup getUsersGroup()
 */
class User extends Entity implements EntityWithGetSetInterface, IdentityInterface
{
    use GetSetTrait;
    use UserMethodsTrait;
    use UsersGroupMethodsTrait;
    use UserStatusMethodsTrait;
}
