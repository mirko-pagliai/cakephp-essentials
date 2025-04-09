<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Essentials\ORM\Entity\Traits\GetFullNameTrait;
use Cake\Essentials\ORM\Entity\Traits\GetSetTrait;
use Cake\ORM\Entity;

/**
 * Person Entity.
 */
class Person extends Entity
{
    use GetFullNameTrait;
    use GetSetTrait;
}
