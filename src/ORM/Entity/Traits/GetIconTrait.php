<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Entity\Traits;

/**
 * This trait automatically implements the `getIcon()` method, which returns the `icon` property, if exists and is
 *  valid, otherwise returns `getStaticIcon()`.
 *
 * @property string|null $icon
 *
 * @psalm-require-implements \Cake\Essentials\ORM\Entity\EntityWithIconsInterface
 */
trait GetIconTrait
{
    /**
     * Returns the icon for this instantiated entity.
     *
     * Returns the `icon` property, if exists for this entity and is valid, otherwise returns `getStaticIcon()`.
     *
     * @return non-empty-string
     */
    public function getIcon(): string
    {
        $icon = $this->get('icon');

        return $icon && is_string($icon) ? $icon : $this->getStaticIcon();
    }
}
