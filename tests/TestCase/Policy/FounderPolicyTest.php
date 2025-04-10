<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\Policy;

use Cake\Essentials\ORM\Entity\UserInterface;
use Cake\Essentials\Policy\FounderPolicy;
use Cake\TestSuite\TestCase;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * FounderPolicyTest.
 */
#[CoversClass(FounderPolicy::class)]
class FounderPolicyTest extends TestCase
{
    /**
     * @var \Cake\Essentials\Policy\FounderPolicy
     */
    protected FounderPolicy $Policy;

    /**
     * @inheritDoc
     */
    #[Override]
    protected function setUp(): void
    {
        $this->Policy = new class extends FounderPolicy {
        };
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    #[TestWith([true, true])]
    #[TestWith([false, false])]
    public function testCanAdd(bool $expectedResult, bool $isFounder): void
    {
        $Identity = $this->createConfiguredMock(UserInterface::class, compact('isFounder'));

        $result = $this->Policy->canAdd($Identity);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    #[TestWith([true, true])]
    #[TestWith([false, false])]
    public function testCanEdit(bool $expectedResult, bool $isFounder): void
    {
        $Identity = $this->createConfiguredMock(UserInterface::class, compact('isFounder'));

        $result = $this->Policy->canEdit($Identity);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    #[TestWith([true, true])]
    #[TestWith([false, false])]
    public function testCanDelete(bool $expectedResult, bool $isFounder): void
    {
        $Identity = $this->createConfiguredMock(UserInterface::class, compact('isFounder'));

        $result = $this->Policy->canDelete($Identity);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    #[TestWith([true, true])]
    #[TestWith([false, false])]
    public function testCanView(bool $expectedResult, bool $isFounder): void
    {
        $Identity = $this->createConfiguredMock(UserInterface::class, compact('isFounder'));

        $result = $this->Policy->canView($Identity);
        $this->assertSame($expectedResult, $result);
    }
}
