<?php
declare(strict_types=1);

namespace App\Model\Enum;

use Cake\Database\Type\EnumLabelInterface;
use Cake\Essentials\ORM\Enum\Traits\EnumWithGetLabelsTrait;
use Override;

/**
 * Floor.
 *
 * This is an enum that implements `EnumLabelInterface` and uses `EnumWithGetLabelsTrait`.
 *
 * So it can be used to test `EnumWithGetLabelsTrait`.
 */
enum Floor: int implements EnumLabelInterface
{
    use EnumWithGetLabelsTrait;

    case First = 1;

    case Second = 2;

    /**
     * @inheritDoc
     */
    #[Override]
    public function label(): string
    {
        return match ($this) {
            self::First => 'First floor',
            self::Second => 'Second floor',
        };
    }
}
