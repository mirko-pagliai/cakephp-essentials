<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\ORM\Entity\Traits;

use App\Model\Entity\User;
use App\Model\Entity\UsersGroup;
use Cake\Essentials\ORM\Entity\Traits\UsersGroupMethodsTrait;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * UsersGroupMethodsTraitTest.
 */
#[CoversTrait(UsersGroupMethodsTrait::class)]
class UsersGroupMethodsTraitTest extends TestCase
{
    #[Test]
    #[TestWith([true, 'admin'])]
    #[TestWith([true, 'admin', 'manager'])]
    #[TestWith([false, 'manager'])]
    #[TestWith([false, 'guest'])]
    public function testIsGroup(bool $expectedIsGroup, string ...$groups): void
    {
        $User = new User(['users_group' => new UsersGroup(['name' => 'admin'])]);

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
}
