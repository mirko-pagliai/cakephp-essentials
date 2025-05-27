<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\ORM\Entity\Traits;

use Cake\Essentials\ORM\Entity\EntityWithGetSetInterface;
use Cake\Essentials\ORM\Entity\Traits\GetFullNameTrait;
use Cake\Essentials\ORM\Entity\Traits\GetSetTrait;
use Cake\Essentials\TestSuite\TestCase;
use Cake\ORM\Entity;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * GetFullNameTraitTest.
 */
#[CoversTrait(GetFullNameTrait::class)]
class GetFullNameTraitTest extends TestCase
{
    /**
     * @var \Cake\Essentials\ORM\Entity\EntityWithGetSetInterface
     */
    protected EntityWithGetSetInterface $Person;

    protected function setUp(): void
    {
        $this->Person = new class extends Entity implements EntityWithGetSetInterface {
            use GetFullNameTrait;
            use GetSetTrait;
        };
    }

    #[Test]
    #[TestWith(['Red Mark', 'Red', 'Mark'])]
    #[TestWith([null, 'Red', null])]
    #[TestWith([null, null, 'Mark'])]
    #[TestWith([null, null, null])]
    public function testGetFullNameVirtualField(?string $expectedFullName, ?string $lastName, ?string $firstName): void
    {
        $this->Person->set(['last_name' => $lastName, 'first_name' => $firstName]);
        $this->assertSame($expectedFullName, $this->Person->get('full_name'));
    }

    #[Test]
    public function testGetFullName(): void
    {
        $this->Person->set(['last_name' => 'Red', 'first_name' => 'Mark']);
        /** @phpstan-ignore method.notFound */
        $this->assertSame('Red Mark', $this->Person->getFullName());
    }
}
