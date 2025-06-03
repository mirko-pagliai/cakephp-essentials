<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authorization\IdentityInterface;
use Cake\Essentials\ORM\Entity\Traits\GetSetTrait;
use Cake\Essentials\ORM\Entity\Traits\UserIdentityTrait;
use Cake\Essentials\ORM\Entity\Traits\UserMethodsTrait;
use Cake\Essentials\ORM\Entity\Traits\UserStatusMethodsTrait;
use Cake\Essentials\ORM\Entity\UserIdentityInterface;
use Cake\Essentials\ORM\Entity\UserInterface;
use Cake\ORM\Entity;

/**
 * User Entity.
 *
 * @property string $password
 * @property \Cake\Essentials\ORM\Enum\UserStatus $status
 *
 * @method \Cake\Essentials\ORM\Enum\UserStatus getStatus()
 */
class User extends Entity implements UserIdentityInterface, UserInterface
{
    use GetSetTrait;
    use UserIdentityTrait;
    use UserMethodsTrait;
    use UserStatusMethodsTrait;
}
