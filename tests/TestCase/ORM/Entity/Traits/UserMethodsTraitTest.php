<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\ORM\Entity\Traits;

use App\Model\Entity\User;
use Cake\Essentials\ORM\Entity\EntityWithGetSetInterface;
use Cake\Essentials\ORM\Entity\Traits\GetSetTrait;
use Cake\Essentials\ORM\Entity\Traits\UserMethodsTrait;
use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * UserMethodsTraitTest.
 */
#[CoversTrait(UserMethodsTrait::class)]
class UserMethodsTraitTest extends TestCase
{
    #[Test]
    public function testSetPassword(): void
    {
        $password = 'myPassword';
        $User = new User(compact('password'));
        $this->assertNotEmpty($User->password);
        $this->assertNotEquals($password, $User->password);
    }

    #[Test]
    #[TestWith([true, 2])]
    #[TestWith([true, 2, 3])]
    #[TestWith([false, 3])]
    #[TestWith([false, 3, 4])]
    public function testIsId(bool $expectedIsId, int ...$id): void
    {
        $User = new User(['id' => 2]);

        $this->assertSame($expectedIsId, $User->isId(...$id));
    }

    #[Test]
    #[TestWith([true, 'admin'])]
    #[TestWith([true, 'admin', 'manager'])]
    #[TestWith([false, 'manager'])]
    #[TestWith([false, 'guest'])]
    public function testIsGroup(bool $expectedIsGroup, string ...$groups): void
    {
        $UsersGroup = new class (['name' => 'admin']) extends Entity implements EntityWithGetSetInterface {
            use GetSetTrait;
        };
        $User = new User(['users_group' => $UsersGroup]);

        $this->assertSame($expectedIsGroup, $User->isGroup(...$groups));
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    public function testIsAdmin(): void
    {
        $User = $this->createPartialMock(User::class, ['isGroup']);
        $User
            ->expects($this->any())
            ->method('isGroup')
            ->with('admin');

        $User->isAdmin();
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    public function testIsManager(): void
    {
        $User = $this->createPartialMock(User::class, ['isGroup']);
        $User
            ->expects($this->any())
            ->method('isGroup')
            ->with('admin', 'manager');

        $User->isManager();
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    public function testIsFounder(): void
    {
        $User = $this->createPartialMock(User::class, ['isId']);
        $User
            ->expects($this->once())
            ->method('isId')
            ->with(1);

        $User->isFounder();
    }

    #[Test]
    #[TestWith([true, 4])]
    #[TestWith([false, 3])]
    public function testIsOwnerOf(bool $expectedIsOwner, int $userId): void
    {
        $Article = new class (['user_id' => 4]) extends Entity implements EntityWithGetSetInterface {
            use GetSetTrait;
        };
        $User = new User(['id' => $userId]);
        $this->assertSame($expectedIsOwner, $User->isOwnerOf($Article));
    }
}
