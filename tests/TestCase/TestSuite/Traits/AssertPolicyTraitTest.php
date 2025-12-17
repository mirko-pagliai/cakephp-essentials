<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\TestSuite\Traits;

use App\Model\Entity\Article;
use App\Model\Entity\User;
use Authorization\AuthorizationService;
use Authorization\Policy\Exception\MissingMethodException;
use Authorization\Policy\OrmResolver;
use Cake\Essentials\TestSuite\Traits\AssertPolicyTrait;
use Error;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * AssertPolicyTraitTest.
 *
 * This class extends `PHPUnit\Framework\TestCase`, to avoid conflicts.
 *
 * @see \App\Policy\ArticlePolicy The policy used in this test
 */
#[CoversTrait(AssertPolicyTrait::class)]
class AssertPolicyTraitTest extends TestCase
{
    protected Article $Article;

    protected User $User;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        $this->Article = new Article();

        $this->User = new User(['id' => 1]);
        $this->User->setAuthorization(new AuthorizationService(new OrmResolver()));
    }

    /**
     * @link \Cake\Essentials\TestSuite\Traits\AssertPolicyTrait::assertPolicyResult()
     */
    #[Test]
    public function testAssertPolicyResult(): void
    {
        $TestCase = new class ('Test') extends TestCase {
            use AssertPolicyTrait;
        };

        $AnotherUser = new User(['id' => 2]);
        $AnotherUser->setAuthorization(new AuthorizationService(new OrmResolver()));

        $TestCase->assertPolicyResult(expectedResult: true, method: 'add', Identity: $this->User, Entity: $this->Article);
        $TestCase->assertPolicyResult(expectedResult: false, method: 'add', Identity: $AnotherUser, Entity: $this->Article);

        // It is `false` for both, because `edit` requires a defined entity to get `true` as a result
        $TestCase->assertPolicyResult(expectedResult: false, method: 'edit', Identity: $this->User, Entity: $this->Article);
        $TestCase->assertPolicyResult(expectedResult: false, method: 'edit', Identity: $AnotherUser, Entity: $this->Article);

        $this->Article->set('status', true);

        $TestCase->assertPolicyResult(expectedResult: false, method: 'edit', Identity: $this->User, Entity: $this->Article);
        $TestCase->assertPolicyResult(expectedResult: true, method: 'edit', Identity: $AnotherUser, Entity: $this->Article);

        $this->Article->set('status', false);

        $TestCase->assertPolicyResult(expectedResult: false, method: 'edit', Identity: $this->User, Entity: $this->Article);
        $TestCase->assertPolicyResult(expectedResult: false, method: 'edit', Identity: $AnotherUser, Entity: $this->Article);
    }

    /**
     * @link \Cake\Essentials\TestSuite\Traits\AssertPolicyTrait::assertPolicyResult()
     */
    #[Test]
    public function testAssertPolicyResultOnFailure(): void
    {
        $TestCase = new class ('Test') extends TestCase {
            use AssertPolicyTrait;
        };

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('`edit()` method has returned `false`');
        $TestCase->assertPolicyResult(expectedResult: true, method: 'edit', Identity: $this->User, Entity: $this->Article);
    }

    /**
     * @link \Cake\Essentials\TestSuite\Traits\AssertPolicyTrait::assertPolicyResult()
     */
    #[Test]
    public function testAssertPolicyWithNoExistingPolicyResultMethod(): void
    {
        $TestCase = new class ('Test') extends TestCase {
            use AssertPolicyTrait;
        };

        try {
            $TestCase->assertPolicyResult(
                expectedResult: true,
                method: 'invalidAction',
                Identity: $this->User,
                Entity: $this->Article,
            );

            $this->fail('Expected exception not thrown.');
        } catch (Throwable $e) {
            $this->assertTrue(
                ($e instanceof MissingMethodException && $e->getMessage() === 'Method `canInvalidAction` for invoking action `invalidAction` has not been defined in `App\Policy\ArticlePolicy`.') ||
                ($e instanceof Error && str_contains($e->getMessage(), 'Call to undefined method')),
            );
        }
    }

    /**
     * @link \Cake\Essentials\TestSuite\Traits\AssertPolicyTrait::assertPolicyResultFalse()
     */
    #[Test]
    public function testAssertPolicyResultFalse(): void
    {
        $TestCase = new class ('Test') extends TestCase {
            use AssertPolicyTrait;
        };

        $TestCase->assertPolicyResultFalse(method: 'edit', Identity: $this->User, Entity: $this->Article);
    }

    /**
     * @link \Cake\Essentials\TestSuite\Traits\AssertPolicyTrait::assertPolicyResultTrue()
     */
    #[Test]
    public function testAssertPolicyResultTrue(): void
    {
        $TestCase = new class ('Test') extends TestCase {
            use AssertPolicyTrait;
        };

        $TestCase->assertPolicyResultTrue(method: 'add', Identity: $this->User, Entity: $this->Article);
    }
}
