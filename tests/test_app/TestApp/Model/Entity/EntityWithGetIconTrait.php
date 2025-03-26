<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Essentials\ORM\EntityWithIconsInterface;
use Cake\Essentials\ORM\Traits\GetIconTrait;
use Cake\ORM\Entity;
use Override;

/**
 * EntityWithGetIconTrait.
 */
class EntityWithGetIconTrait extends Entity implements EntityWithIconsInterface
{
    use GetIconTrait;

    #[Override]
    public static function getStaticIcon(): string
    {
        return 'house';
    }
}
