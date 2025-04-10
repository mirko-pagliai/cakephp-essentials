<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\ORM\Entity\Traits;

use App\Model\Entity\Person;
use Cake\Essentials\ORM\Entity\Traits\GetFullNameTrait;
use Cake\Essentials\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * GetFullNameTraitTest.
 */
#[CoversTrait(GetFullNameTrait::class)]
class GetFullNameTraitTest extends TestCase
{
    #[Test]
    #[TestWith(['Red Mark', 'Red', 'Mark'])]
    #[TestWith([null, 'Red', null])]
    #[TestWith([null, null, 'Mark'])]
    #[TestWith([null, null, null])]
    public function testGetFullNameVirtualField(?string $expectedFullName, ?string $lastName, ?string $firstName): void
    {
        $Person = new Person(['last_name' => $lastName, 'first_name' => $firstName]);

        $this->assertSame($expectedFullName, $Person->full_name);
    }

    #[Test]
    public function testGetFullName(): void
    {
        $Person = new Person(['last_name' => 'Red', 'first_name' => 'Mark']);

        $this->assertSame('Red Mark', $Person->getFullName());
    }
}
