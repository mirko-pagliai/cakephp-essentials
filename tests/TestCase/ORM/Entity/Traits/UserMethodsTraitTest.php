<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\ORM\Entity\Traits;

use App\Model\Entity\User;
use Cake\Essentials\ORM\Entity\EntityWithGetSetInterface;
use Cake\Essentials\ORM\Entity\Traits\GetSetTrait;
use Cake\Essentials\ORM\Entity\Traits\UserMethodsTrait;
use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;
use Mockery;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use UnexpectedValueException;

/**
 * UserMethodsTraitTest.
 */
#[CoversTrait(UserMethodsTrait::class)]
class UserMethodsTraitTest extends TestCase
{
    /**
     * @link \Cake\Essentials\ORM\Entity\Traits\UserMethodsTrait::_setPassword()
     */
    #[Test]
    public function testSetPassword(): void
    {
        $password = 'myPassword';
        $User = new User(compact('password'));
        $this->assertNotEmpty($User->password);
        $this->assertNotEquals($password, $User->password);
    }

    /**
     * @link \Cake\Essentials\ORM\Entity\Traits\UserMethodsTrait::isId()
     */
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

    /**
     * @link \Cake\Essentials\ORM\Entity\Traits\UserMethodsTrait::isGroup()
     */
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
     * @link \Cake\Essentials\ORM\Entity\Traits\UserMethodsTrait::isGroup()
     */
    #[Test]
    public function testIsGroupOnUsersGroupNotAnEntity(): void
    {
        $User = new User(['users_group' => ['name' => 'admin']]);

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessageIs(
            'The `users_group` property must be an instance of `' . EntityWithGetSetInterface::class . '`',
        );
        $User->isGroup('admin');
    }

    /**
     * @link \Cake\Essentials\ORM\Entity\Traits\UserMethodsTrait::isAdmin()
     */
    #[Test]
    public function testIsAdmin(): void
    {
        /** @var \Mockery\MockInterface&\App\Model\Entity\User $User */
        $User = Mockery::mock(User::class . '[isGroup]');
        $User->shouldReceive('isGroup')
            ->with('admin')
            ->once();

        $User->isAdmin();
    }

    /**
     * @link \Cake\Essentials\ORM\Entity\Traits\UserMethodsTrait::isManager()
     */
    #[Test]
    public function testIsManager(): void
    {
        /** @var \Mockery\MockInterface&\App\Model\Entity\User $User */
        $User = Mockery::mock(User::class . '[isGroup]');
        $User->shouldReceive('isGroup')
            ->with('admin', 'manager')
            ->once();

        $User->isManager();
    }

    /**
     * @link \Cake\Essentials\ORM\Entity\Traits\UserMethodsTrait::isFounder()
     */
    #[Test]
    public function testIsFounder(): void
    {
        /** @var \Mockery\MockInterface&\App\Model\Entity\User $User */
        $User = Mockery::mock(User::class . '[isId]');
        $User->shouldReceive('isId')
            ->with(1)
            ->once();

        $User->isFounder();
    }

    /**
     * @link \Cake\Essentials\ORM\Entity\Traits\UserMethodsTrait::isOwnerOf()
     */
    #[Test]
    #[TestWith([true, 4])]
    #[TestWith([false, 3])]
    public function testIsOwnerOf(bool $expectedIsOwner, int $userId): void
    {
        $Article = new class extends Entity implements EntityWithGetSetInterface {
            use GetSetTrait;
        };
        $Article->set('user_id', 4);
        $User = new User(['id' => $userId]);
        $this->assertSame($expectedIsOwner, $User->isOwnerOf($Article));
    }

    /**
     * Tests for the `isOwnerOf()` method when an error occurs.
     *
     * @link \Cake\Essentials\ORM\Entity\Traits\UserMethodsTrait::isOwnerOf()
     */
    #[Test]
    #[TestWith(['4'])]
    #[TestWith([4.0])]
    #[TestWith([0])]
    #[TestWith([-1])]
    public function testIsOwnerOfOnError(mixed $userId): void
    {
        $Article = new class extends Entity implements EntityWithGetSetInterface {
            use GetSetTrait;
        };
        $Article->set('user_id', $userId);
        $User = new User(['id' => 4]);

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessageIs('The `user_id` property must be a positive integer');
        $User->isOwnerOf($Article);
    }
}
