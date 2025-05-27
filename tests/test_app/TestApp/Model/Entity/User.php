<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Essentials\ORM\Entity\Traits\GetSetTrait;
use Cake\Essentials\ORM\Entity\Traits\UserMethodsTrait;
use Cake\Essentials\ORM\Entity\Traits\UserStatusMethodsTrait;
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
class User extends Entity implements UserInterface
{
    use GetSetTrait;
    use UserMethodsTrait;
    use UserStatusMethodsTrait;
}
