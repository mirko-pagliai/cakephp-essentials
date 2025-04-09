<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\test_app\TestApp\Model\Entity;

use App\Model\Entity\User;
use Cake\Essentials\ORM\Entity\Traits\UserMethodsTrait;
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
}
