<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\ORM\Entity\Traits;

use Authorization\AuthorizationServiceInterface;
use Cake\Datasource\EntityInterface;
use Cake\Essentials\ORM\Entity\Traits\GetSetTrait;
use Cake\Essentials\ORM\Entity\Traits\UserIdentityTrait;
use Cake\Essentials\ORM\Entity\UserIdentityInterface;
use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

/**
 * UserIdentityTraitTest.
 */
#[CoversClass(UserIdentityTrait::class)]
class UserIdentityTraitTest extends TestCase
{
    /**
     * @var \Authorization\AuthorizationServiceInterface&\PHPUnit\Framework\MockObject\MockObject
     */
    protected AuthorizationServiceInterface $AuthorizationService;

    /**
     * @var \Cake\Essentials\ORM\Entity\EntityWithGetSetInterface&\Cake\Essentials\ORM\Entity\UserIdentityInterface
     */
    protected UserIdentityInterface $Identity;

    /**
     * {@inheritDoc}
     *
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Override]
    protected function setUp(): void
    {
        $this->AuthorizationService = $this->createMock(AuthorizationServiceInterface::class);

        $this->Identity = new class extends Entity implements UserIdentityInterface {
            use GetSetTrait;
            use UserIdentityTrait;
        };
        $this->Identity->setAuthorization($this->AuthorizationService);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    public function testCan(): void
    {
        $Entity = $this->createStub(EntityInterface::class);

        $this->AuthorizationService
            ->expects($this->once())
            ->method('can')
            ->with($this->Identity, 'add', $Entity);

        $this->Identity->can('add', $Entity);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    public function testCanResult(): void
    {
        $Entity = $this->createStub(EntityInterface::class);

        $this->AuthorizationService
            ->expects($this->once())
            ->method('canResult')
            ->with($this->Identity, 'add', $Entity);

        $this->Identity->canResult('add', $Entity);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    public function testApplyScope(): void
    {
        $Entity = $this->createStub(EntityInterface::class);

        $this->AuthorizationService
            ->expects($this->once())
            ->method('applyScope')
            ->with($this->Identity, 'add', $Entity, 'optionalExtraArg');

        $this->Identity->applyScope('add', $Entity, 'optionalExtraArg');
    }

    #[Test]
    public function testGetIdentifier(): void
    {
        $this->Identity->set('id', 2);
        $this->assertSame(2, $this->Identity->getIdentifier());
    }

    #[Test]
    public function testGetOriginalData(): void
    {
        $result = $this->Identity->getOriginalData();
        $this->assertSame($this->Identity, $result);
    }

    #[Test]
    public function testSetAuthorization(): void
    {
        $Identity = new class extends Entity implements UserIdentityInterface {
            use GetSetTrait;
            use UserIdentityTrait;
        };

        $result = $Identity->setAuthorization($this->AuthorizationService);

        $this->assertSame($result, $Identity);
        $this->assertSame($this->AuthorizationService, $Identity->Authorization);
    }
}
