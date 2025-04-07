<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\ORM\Enum;

use Cake\Essentials\ORM\Enum\UserStatus;
use Cake\Essentials\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

/**
 * UserStatusTest.
 */
#[CoversClass(UserStatus::class)]
class UserStatusTest extends TestCase
{
    #[Test]
    public function testLabel(): void
    {
        $expected = [
            'active' => 'Active',
            'disabled' => 'Disabled',
            'requires_user_activation' => 'Requires user activation',
            'requires_admin_activation' => 'Requires administrator activation',
        ];

        foreach ($expected as $key => $label) {
            $this->assertSame($label, UserStatus::from($key)->label());
        }
    }
}
