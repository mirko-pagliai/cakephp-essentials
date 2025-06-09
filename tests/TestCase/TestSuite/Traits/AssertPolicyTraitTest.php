<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\TestSuite\Traits;

use App\Model\Entity\Article;
use App\Model\Entity\User;
use Authorization\AuthorizationServiceInterface;
use Authorization\Policy\Exception\MissingMethodException;
use Cake\Essentials\TestSuite\Traits\AssertPolicyTrait;
use Mockery;
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
    protected Article $Article;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->Article = new Article();
    }

    #[Test]
    public function testAssertPolicyResult(): void
    {
        $TestCase = new class ('Test') extends TestCase {
            use AssertPolicyTrait;
        };

        $FirstUser = new User(['id' => 1]);
        $SecondUser = new User(['id' => 2]);

        $TestCase->assertPolicyResult(expectedResult: true, method: 'canAdd', Identity: $FirstUser, Entity: $this->Article);
        $TestCase->assertPolicyResult(expectedResult: false, method: 'canAdd', Identity: $SecondUser, Entity: $this->Article);

        // It is `false` for both, because `canEdit` requires a defined entity to get `true` as a result
        $TestCase->assertPolicyResult(expectedResult: false, method: 'canEdit', Identity: $FirstUser, Entity: $this->Article);
        $TestCase->assertPolicyResult(expectedResult: false, method: 'canEdit', Identity: $SecondUser, Entity: $this->Article);

        $this->Article->set('status', true);

        $TestCase->assertPolicyResult(expectedResult: false, method: 'canEdit', Identity: $FirstUser, Entity: $this->Article);
        $TestCase->assertPolicyResult(expectedResult: true, method: 'canEdit', Identity: $SecondUser, Entity: $this->Article);

        $this->Article->set('status', false);

        $TestCase->assertPolicyResult(expectedResult: false, method: 'canEdit', Identity: $FirstUser, Entity: $this->Article);
        $TestCase->assertPolicyResult(expectedResult: false, method: 'canEdit', Identity: $SecondUser, Entity: $this->Article);
    }

    #[Test]
    public function testAssertPolicyOnFailure(): void
    {
        $TestCase = new class ('Test') extends TestCase {
            use AssertPolicyTrait;
        };

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('`edit()` method has returned `false`');
        $TestCase->assertPolicyResult(expectedResult: true, method: 'canEdit', Identity: new User(), Entity: $this->Article);
    }

    #[Test]
    public function testAssertPolicyWithNoExistingPolicyMethod(): void
    {
        $TestCase = new class ('Test') extends TestCase {
            use AssertPolicyTrait;
        };

        $this->expectException(MissingMethodException::class);
        $this->expectExceptionMessage('Method `canNotExist` for invoking action `notExist` has not been defined in `App\Policy\ArticlePolicy`.');
        $TestCase->assertPolicyResult(expectedResult: true, method: 'notExist', Identity: new User(), Entity: $this->Article);
    }

    #[Test]
    public function testAssertPolicyWithCustomAuthorization(): void
    {
        $TestCase = new class ('Test') extends TestCase {
            use AssertPolicyTrait;
        };

        $User = new User(['id' => 1]);

        /** @var \Authorization\AuthorizationServiceInterface&\Mockery\MockInterface $Authorization */
        $Authorization = Mockery::mock(AuthorizationServiceInterface::class);
        $Authorization->shouldReceive('can')
            ->once()
            ->with($User, 'add', $this->Article)
            ->andReturn(true);

        $User->setAuthorization($Authorization);

        $TestCase->assertPolicyResult(expectedResult: true, method: 'canAdd', Identity: $User, Entity: $this->Article);
    }

    #[Test]
    public function testAssertPolicyResultFalse(): void
    {
        $TestCase = new class ('Test') extends TestCase {
            use AssertPolicyTrait;
        };

        $TestCase->assertPolicyResultFalse(method: 'canAdd', Identity: new User(['id' => 2]), Entity: $this->Article);
    }

    #[Test]
    public function testAssertPolicyResultTrue(): void
    {
        $TestCase = new class ('Test') extends TestCase {
            use AssertPolicyTrait;
        };

        $TestCase->assertPolicyResultTrue(method: 'canAdd', Identity: new User(['id' => 1]), Entity: $this->Article);
    }
}
