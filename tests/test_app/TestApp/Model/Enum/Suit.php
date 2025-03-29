<?php
declare(strict_types=1);

namespace App\Model\Enum;

use Cake\Database\Type\EnumLabelInterface;
use Cake\Essentials\ORM\Enum\Traits\EnumWithGetLabelsTrait;
use Override;

/**
 * Suit.
 *
 * This is an enum that implements `EnumLabelInterface` and uses `EnumWithGetLabelsTrait`.
 *
 * So it can be used to test `EnumWithGetLabelsTrait`.
 */
enum Suit: string implements EnumLabelInterface
{
    use EnumWithGetLabelsTrait;

    case Hearts = 'H';

    case Diamonds = 'D';

    case Clubs = 'C';

    case Spades = 'S';

    /**
     * @inheritDoc
     */
    #[Override]
    public function label(): string
    {
        return match ($this) {
            self::Hearts => 'Hearts suit',
            self::Diamonds => 'Diamonds suit',
            self::Clubs => 'Clubs suit',
            self::Spades => 'Spades suit',
        };
    }
}
