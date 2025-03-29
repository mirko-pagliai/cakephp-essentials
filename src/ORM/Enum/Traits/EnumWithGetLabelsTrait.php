<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Enum\Traits;

use Cake\Database\Type\EnumLabelInterface;

/**
 * This trait can be used by an Enum that implements `EnumLabelInterface`.
 *
 * It actually provides the `getLabels()` method.
 *
 * @psalm-require-implements \Cake\Database\Type\EnumLabelInterface
 */
trait EnumWithGetLabelsTrait
{
    /**
     * Returns an array with all labels of all cases.
     *
     * @return array<array-key, string>
     */
    public static function getLabels(): array
    {
        $cases = self::cases();

        return array_combine(
            keys: array_column(array: $cases, column_key: 'value'),
            values: array_map(
                callback: fn (EnumLabelInterface $Enum): string => $Enum->label(),
                array: $cases
            )
        );
    }
}
