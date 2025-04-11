<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\ORM\Table\Traits;

use Cake\Essentials\ORM\Enum\UserStatus;
use Cake\Essentials\ORM\Table\Traits\UserStatusFindMethodsTrait;
use Cake\Essentials\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * UserStatusFindMethodsTraitTest.
 */
#[CoversTrait(UserStatusFindMethodsTrait::class)]
#[UsesClass(UserStatus::class)]
class UserStatusFindMethodsTraitTest extends TestCase
{

}
