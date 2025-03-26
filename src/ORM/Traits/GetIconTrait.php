<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Traits;

/**
 * This trait automatically implements the `getIcon()` method, which returns the `icon` property or `getStaticIcon()`.
 *
 * @property string|null $icon
 * @psalm-require-extends \Cake\ORM\Entity
 * @psalm-require-implements \Cake\Essentials\ORM\EntityWithIconsInterface
 */
trait GetIconTrait
{
    /**
     * Returns the static icon for this entity type.
     *
     * Returns the `icon` property, if exists for this entity and is a string. Otherwise, returns `getStaticIcon()`.
     *
     * @return string
     */
    public function getIcon(): string
    {
        $icon = $this->get('icon');

        return is_string($icon) ? $icon : $this->getStaticIcon();
    }
}
