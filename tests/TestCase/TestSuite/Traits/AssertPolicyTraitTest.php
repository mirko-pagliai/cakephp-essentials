<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\TestSuite\Traits;

use App\Model\Entity\User;
use App\Policy\ExamplePolicy;
use Cake\Essentials\TestSuite\Traits\AssertPolicyTrait;
use Cake\ORM\Entity;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * AssertPolicyTraitTest.
 *
 * This class extends `PHPUnit\Framework\TestCase`, to avoid conflicts.
 */
#[CoversTrait(AssertPolicyTrait::class)]
class AssertPolicyTraitTest extends TestCase
{
    #[Test]
    public function testAssertPolicy(): void
    {
        $TestCase = new class ('Test') extends TestCase {
            use AssertPolicyTrait;
        };

        $FirstUser = new User(['id' => 1]);
        $SecondUser = new User(['id' => 2]);

        $TestCase->assertPolicyResult(true, ExamplePolicy::class, 'canAdd', $FirstUser);
        $TestCase->assertPolicyResult(false, ExamplePolicy::class, 'canAdd', $SecondUser);

        // It is `false` for both, because `canEdit` requires a defined entity to get `true` as a result
        $TestCase->assertPolicyResult(false, ExamplePolicy::class, 'canEdit', $FirstUser);
        $TestCase->assertPolicyResult(false, ExamplePolicy::class, 'canEdit', $SecondUser);

        $Entity = new Entity(['status' => true]);

        $TestCase->assertPolicyResult(false, ExamplePolicy::class, 'canEdit', $FirstUser, $Entity);
        $TestCase->assertPolicyResult(true, ExamplePolicy::class, 'canEdit', $SecondUser, $Entity);

        $Entity = new Entity(['status' => false]);

        $TestCase->assertPolicyResult(false, ExamplePolicy::class, 'canEdit', $FirstUser, $Entity);
        $TestCase->assertPolicyResult(false, ExamplePolicy::class, 'canEdit', $SecondUser, $Entity);
    }

    #[Test]
    public function testAssertPolicyOnFailure(): void
    {
        $TestCase = new class ('Test') extends TestCase {
            use AssertPolicyTrait;
        };

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('`App\Policy\ExamplePolicy::canEdit()` method has returned `false`');
        $TestCase->assertPolicyResult(true, ExamplePolicy::class, 'canEdit', new User());
    }

    #[Test]
    public function testAssertPolicyOnFailureWithUsersGroup(): void
    {
        $TestCase = new class ('Test') extends TestCase {
            use AssertPolicyTrait;
        };

        $User = new User(['users_group' => new Entity(['name' => 'admin'])]);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('`App\Policy\ExamplePolicy::canEdit()` method has returned `false` for group `admin`');
        $TestCase->assertPolicyResult(true, ExamplePolicy::class, 'canEdit', $User);
    }

    #[Test]
    public function testAssertPolicyWithNoExistingPolicyMethod(): void
    {
        $TestCase = new class ('Test') extends TestCase {
            use AssertPolicyTrait;
        };

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Policy `App\Policy\ExamplePolicy` does not have a `canDoSomethingDoesNotExist()` method');
        $TestCase->assertPolicyResult(true, ExamplePolicy::class, 'canDoSomethingDoesNotExist', new User());
    }
}
