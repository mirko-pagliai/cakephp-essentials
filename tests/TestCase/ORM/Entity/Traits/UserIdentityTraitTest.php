<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\ORM\Entity\Traits;

use Authorization\AuthorizationServiceInterface;
use Cake\Essentials\ORM\Entity\Traits\GetSetTrait;
use Cake\Essentials\ORM\Entity\Traits\UserIdentityTrait;
use Cake\Essentials\ORM\Entity\UserIdentityInterface;
use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;
use Mockery;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;

/**
 * UserIdentityTraitTest.
 */
#[CoversTrait(UserIdentityTrait::class)]
class UserIdentityTraitTest extends TestCase
{
    /**
     * @var \Mockery\MockInterface&\Authorization\AuthorizationServiceInterface
     */
    protected AuthorizationServiceInterface $AuthorizationService;

    protected UserIdentityInterface $Identity;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        /** @var \Mockery\MockInterface&\Authorization\AuthorizationServiceInterface $AuthorizationService */
        $AuthorizationService = Mockery::mock(AuthorizationServiceInterface::class);
        $this->AuthorizationService = $AuthorizationService;

        $this->Identity = new class extends Entity implements UserIdentityInterface {
            use GetSetTrait;
            use UserIdentityTrait;
        };
        $this->Identity->setAuthorization($this->AuthorizationService);
    }

    /**
     * @link \Cake\Essentials\ORM\Entity\Traits\UserIdentityTrait::can()
     */
    #[Test]
    public function testCan(): void
    {
        $Entity = new Entity();

        $this->AuthorizationService
            ->shouldReceive('can')
            ->with($this->Identity, 'add', $Entity)
            ->once();

        $this->Identity->can('add', $Entity);
    }

    /**
     * @link \Cake\Essentials\ORM\Entity\Traits\UserIdentityTrait::canResult()
     */
    #[Test]
    public function testCanResult(): void
    {
        $Entity = new Entity();

        $this->AuthorizationService
            ->shouldReceive('canResult')
            ->with($this->Identity, 'add', $Entity)
            ->once();

        $this->Identity->canResult('add', $Entity);
    }

    /**
     * @link \Cake\Essentials\ORM\Entity\Traits\UserIdentityTrait::applyScope()
     */
    #[Test]
    public function testApplyScope(): void
    {
        $Entity = new Entity();

        $this->AuthorizationService
            ->shouldReceive('applyScope')
            ->with($this->Identity, 'add', $Entity, 'optionalExtraArg')
            ->once();

        $this->Identity->applyScope('add', $Entity, 'optionalExtraArg');
    }

    /**
     * @link \Cake\Essentials\ORM\Entity\Traits\UserIdentityTrait::getIdentifier()
     */
    #[Test]
    public function testGetIdentifier(): void
    {
        $this->Identity->set('id', 2);
        $this->assertSame(2, $this->Identity->getIdentifier());
    }

    /**
     * @link \Cake\Essentials\ORM\Entity\Traits\UserIdentityTrait::getOriginalData()
     */
    #[Test]
    public function testGetOriginalData(): void
    {
        $result = $this->Identity->getOriginalData();
        $this->assertSame($this->Identity, $result);
    }

    /**
     * @link \Cake\Essentials\ORM\Entity\Traits\UserIdentityTrait::setAuthorization()
     */
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
