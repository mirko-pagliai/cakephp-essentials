<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\ORM\Traits;

use App\Model\Entity\EntityWithGetIconTrait;
use Cake\Essentials\ORM\Traits\GetIconTrait;
use Cake\TestSuite\TestCase;
use Override;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * GetIconTraitTest.
 */
#[CoversTrait(GetIconTrait::class)]
class GetIconTraitTest extends TestCase
{
    /**
     * @var \App\Model\Entity\EntityWithGetIconTrait
     */
    protected EntityWithGetIconTrait $Entity;

    /**
     * @inheritDoc
     */
    #[Override]
    protected function setUp(): void
    {
        $this->Entity = new EntityWithGetIconTrait();
    }

    #[Test]
    public function testGetIcon(): void
    {
        $this->assertSame($this->Entity->getStaticIcon(), $this->Entity->getIcon());
    }

    #[Test]
    public function testGetIconWithIconProperty(): void
    {
        $this->Entity->set('icon', 'book');
        $this->assertSame($this->Entity->get('icon'), $this->Entity->getIcon());
    }

    #[Test]
    #[TestWith([''])]
    #[TestWith([null])]
    #[TestWith([false])]
    public function testGetIconWithInvalidIconProperty(mixed $invalidPropertyValue): void
    {
        $this->Entity->set('icon', $invalidPropertyValue);
        $this->assertSame($this->Entity->getStaticIcon(), $this->Entity->getIcon());
    }
}
