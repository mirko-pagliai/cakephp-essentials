<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\ORM\Entity\Traits;

use Cake\Essentials\ORM\Entity\EntityWithIconsInterface;
use Cake\Essentials\ORM\Entity\Traits\GetIconTrait;
use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * GetIconTraitTest.
 */
#[CoversTrait(GetIconTrait::class)]
class GetIconTraitTest extends TestCase
{
    protected EntityWithIconsInterface $Entity;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->Entity = new class extends Entity implements EntityWithIconsInterface {
            use GetIconTrait;

            public static function getStaticIcon(): string
            {
                return 'house';
            }
        };
    }

    /**
     * @link \Cake\Essentials\ORM\Entity\Traits\GetIconTrait::getIcon()
     */
    #[Test]
    public function testGetIcon(): void
    {
        $result = $this->Entity->getIcon();
        $this->assertSame('house', $result);
    }

    /**
     * @link \Cake\Essentials\ORM\Entity\Traits\GetIconTrait::getIcon()
     */
    #[Test]
    public function testGetIconWithIconProperty(): void
    {
        $this->Entity->set('icon', 'book');

        $result = $this->Entity->getIcon();
        $this->assertSame('book', $result);
    }

    /**
     * Tests for the `getIcon()` method with invalid icon property value.
     *
     * In this case, the `icon` property has been set. But since it is invalid, it still returns the `getStaticIcon()`
     *  value.
     *
     * @link \Cake\Essentials\ORM\Entity\Traits\GetIconTrait::getIcon()
     */
    #[Test]
    #[TestWith([''])]
    #[TestWith([null])]
    #[TestWith([false])]
    public function testGetIconWithInvalidIconProperty(mixed $invalidPropertyValue): void
    {
        $this->Entity->set('icon', $invalidPropertyValue);

        $result = $this->Entity->getIcon();
        $this->assertSame('house', $result);
    }
}
