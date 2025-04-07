<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\ORM\Entity\Traits;

use App\Model\Entity\User;
use Cake\Essentials\ORM\Entity\Traits\UserStatusMethodsTrait;
use Cake\Essentials\ORM\Enum\UserStatus;
use Cake\TestSuite\TestCase;
use Generator;
use Override;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * UserStatusMethodsTraitTest.
 */
#[CoversTrait(UserStatusMethodsTrait::class)]
class UserStatusMethodsTraitTest extends TestCase
{
    /**
     * @var \App\Model\Entity\User
     */
    protected User $User;

    /**
     * @inheritDoc
     */
    #[Override]
    protected function setUp(): void
    {
        $this->User = new User();
    }

    public static function providerTestIsActive(): Generator
    {
        yield [true, UserStatus::Active];

        // All other cases except `Active`
        $otherCases = array_filter(
            array: UserStatus::cases(),
            callback: fn (UserStatus $UserStatus): bool => $UserStatus !== UserStatus::Active
        );
        foreach ($otherCases as $UserStatus) {
            yield [false, $UserStatus];
        }
    }

    #[Test]
    #[DataProvider('providerTestIsActive')]
    public function testIsActive(bool $expectedIsActive, UserStatus $UserStatus): void
    {
        $this->User->status = $UserStatus;

        $this->assertSame($expectedIsActive, $this->User->isActive());
    }

    public static function providerTestIsDisabled(): Generator
    {
        yield [true, UserStatus::Disabled];

        // All other cases except `Disabled`
        $otherCases = array_filter(
            array: UserStatus::cases(),
            callback: fn (UserStatus $UserStatus): bool => $UserStatus !== UserStatus::Disabled
        );
        foreach ($otherCases as $UserStatus) {
            yield [false, $UserStatus];
        }
    }

    #[Test]
    #[DataProvider('providerTestIsDisabled')]
    public function testIsDisabled(bool $expectedIsDisabled, UserStatus $UserStatus): void
    {
        $this->User->status = $UserStatus;

        $this->assertSame($expectedIsDisabled, $this->User->isDisabled());
    }

    public static function providerTestIsPending(): Generator
    {
        $pendingStatus = [
            UserStatus::RequiresAdminActivation,
            UserStatus::RequiresUserActivation,
        ];

        foreach ($pendingStatus as $UserStatus) {
            yield [true, $UserStatus];
        }

        // All other cases except `$pendingStatus`
        $otherCases = array_filter(
            array: UserStatus::cases(),
            callback: fn (UserStatus $UserStatus): bool => !in_array(needle: $UserStatus, haystack: $pendingStatus)
        );
        foreach ($otherCases as $UserStatus) {
            yield [false, $UserStatus];
        }
    }

    #[Test]
    #[DataProvider('providerTestIsPending')]
    public function testIsPending(bool $expectedIsPending, UserStatus $UserStatus): void
    {
        $this->User->status = $UserStatus;

        $this->assertSame($expectedIsPending, $this->User->isPending());
    }
}
