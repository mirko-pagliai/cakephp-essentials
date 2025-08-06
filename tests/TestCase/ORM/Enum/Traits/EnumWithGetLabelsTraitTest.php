<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\ORM\Enum\Traits;

use App\Model\Enum\Floor;
use App\Model\Enum\Suit;
use Cake\Essentials\ORM\Enum\Traits\EnumWithGetLabelsTrait;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;

/**
 * EnumWithGetLabelsTraitTest.
 */
#[CoversTrait(EnumWithGetLabelsTrait::class)]
class EnumWithGetLabelsTraitTest extends TestCase
{
    /**
     * @link \Cake\Essentials\ORM\Enum\Traits\EnumWithGetLabelsTrait::getLabels()
     */
    #[Test]
    public function testGetLabels(): void
    {
        $expected = [
            'H' => 'Hearts suit',
            'D' => 'Diamonds suit',
            'C' => 'Clubs suit',
            'S' => 'Spades suit',
        ];
        $this->assertSame($expected, Suit::getLabels());
    }

    /**
     * @link \Cake\Essentials\ORM\Enum\Traits\EnumWithGetLabelsTrait::getLabels()
     */
    #[Test]
    public function testGetLabelsEnumWithIntKeys(): void
    {
        $expected = [
            1 => 'First floor',
            2 => 'Second floor',
        ];
        $this->assertSame($expected, Floor::getLabels());
    }
}
