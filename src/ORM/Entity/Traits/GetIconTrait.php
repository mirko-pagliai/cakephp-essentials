<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Entity\Traits;

/**
 * This trait automatically implements the `getIcon()` method, which returns the `icon` property, if exists and is
 *  valid, otherwise returns `getStaticIcon()`.
 *
 * @property non-empty-string|array<non-empty-string, non-empty-string>|null $icon
 *
 * @phpstan-require-implements \Cake\Essentials\ORM\Entity\EntityWithIconsInterface
 */
trait GetIconTrait
{
    /**
     * Returns the icon for this instantiated entity.
     *
     * Returns the `icon` property if it exists for this entity and is valid, otherwise returns `getStaticIcon()`.
     *
     * @return non-empty-string|non-empty-array<non-empty-string, non-empty-string>
     */
    public function getIcon(): string|array
    {
        $icon = $this->get('icon');

        if ($icon && is_array($icon)) {
            /** @var non-empty-array<non-empty-string, non-empty-string> $icon */
            return $icon;
        }

        return $icon && is_string($icon) ? $icon : $this->getStaticIcon();
    }
}
