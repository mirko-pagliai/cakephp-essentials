<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\Policy;

use Cake\Essentials\ORM\Entity\UserInterface;
use Cake\Essentials\Policy\FounderPolicy;
use Cake\TestSuite\TestCase;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * FounderPolicyTest.
 */
#[CoversClass(FounderPolicy::class)]
class FounderPolicyTest extends TestCase
{
    protected FounderPolicy $Policy;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->Policy = new class extends FounderPolicy {
        };
    }

    /**
     * @link \Cake\Essentials\Policy\FounderPolicy::canAdd()
     */
    #[Test]
    #[TestWith([true, true])]
    #[TestWith([false, false])]
    public function testCanAdd(bool $expectedResult, bool $isFounder): void
    {
        /** @var \Mockery\MockInterface&\Cake\Essentials\ORM\Entity\UserInterface $Identity */
        $Identity = Mockery::mock(UserInterface::class);
        $Identity->shouldReceive('isFounder')
            ->once()
            ->andReturn($isFounder);

        $result = $this->Policy->canAdd($Identity);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @link \Cake\Essentials\Policy\FounderPolicy::canEdit()
     */
    #[Test]
    #[TestWith([true, true])]
    #[TestWith([false, false])]
    public function testCanEdit(bool $expectedResult, bool $isFounder): void
    {
        /** @var \Mockery\MockInterface&\Cake\Essentials\ORM\Entity\UserInterface $Identity */
        $Identity = Mockery::mock(UserInterface::class);
        $Identity->shouldReceive('isFounder')
            ->once()
            ->andReturn($isFounder);

        $result = $this->Policy->canEdit($Identity);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @link \Cake\Essentials\Policy\FounderPolicy::canDelete()
     */
    #[Test]
    #[TestWith([true, true])]
    #[TestWith([false, false])]
    public function testCanDelete(bool $expectedResult, bool $isFounder): void
    {
        /** @var \Mockery\MockInterface&\Cake\Essentials\ORM\Entity\UserInterface $Identity */
        $Identity = Mockery::mock(UserInterface::class);
        $Identity->shouldReceive('isFounder')
            ->once()
            ->andReturn($isFounder);

        $result = $this->Policy->canDelete($Identity);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @link \Cake\Essentials\Policy\FounderPolicy::canView()
     */
    #[Test]
    #[TestWith([true, true])]
    #[TestWith([false, false])]
    public function testCanView(bool $expectedResult, bool $isFounder): void
    {
        /** @var \Mockery\MockInterface&\Cake\Essentials\ORM\Entity\UserInterface $Identity */
        $Identity = Mockery::mock(UserInterface::class);
        $Identity->shouldReceive('isFounder')
            ->once()
            ->andReturn($isFounder);

        $result = $this->Policy->canView($Identity);
        $this->assertSame($expectedResult, $result);
    }
}
