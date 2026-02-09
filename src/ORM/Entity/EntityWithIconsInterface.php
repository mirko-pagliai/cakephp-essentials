<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Entity;

/**
 * This interface can be implemented by entities that want to provide their own icon.
 *
 * It requires implementing:
 * - The `getIcon()` method, which returns the icon (even specific) of the entity when it is instantiated, for example
 *  based on the entity's state conditions or by returning the value of an `icon` property;
 * - The static method `getStaticIcon()`, which returns the (generic) icon of the entity, useful when you want to
 *  retrieve the icon without having an instantiated entity;
 *
 * It is possible to make the entity extend the `GetIconTrait` trait. In this case it will be necessary to implement
 *  only the `getStaticIcon()` method, while the `getIcon()` method implemented by the trait will return the value of
 *  the `icon` property if it exists and is valid, otherwise returns `getStaticIcon()`.
 *
 * Example, implementing both methods:
 * <code>
 * class Articles extends Entity implements EntityWithIconsInterface
 * {
 *    public function getIcon(): string|array
 *    {
 *       return $this->isPublished() ? 'Hand-thumbs-up' : 'hand-thumbs-down';
 *    }
 *
 *    public static function getStaticIcon(): string|array
 *    {
 *       return 'book';
 *    }
 * }
 * </code>
 *
 * Example, implementing only `getStaticIcon()` and using `GetIconTrait` that provides `getIcon()`.
 * <code>
 * class Articles extends Entity implements EntityWithIconsInterface
 * {
 *    use GetIconTrait;
 *
 *    public static function getStaticIcon(): string|array
 *    {
 *       return 'book';
 *    }
 * }
 * </code>
 *
 * These methods, in addition to a string, can also return an array, useful if you want to use a different set of icons
 *  than the default one. For example:
 * <code>
 * public static function getStaticIcon(): string|array
 * {
 *     return [
 *        'name' => 'bullseye',
 *        'namespace' => 'fas',
 *        'prefix' => 'fa',
 *     ];
 * }
 * </code>
 *
 * @phpstan-require-extends \Cake\ORM\Entity
 *
 * @link https://github.com/FriendsOfCake/bootstrap-ui?tab=readme-ov-file#using-a-different-icon-set Using a different icon set
 * @see \Cake\Essentials\ORM\Entity\Traits\GetIconTrait
 */
interface EntityWithIconsInterface
{
    /**
     * Returns the icon for this instantiated entity.
     *
     * @return array{name: string, namespace: string, prefix: string}|string
     */
    public function getIcon(): string|array;

    /**
     * Returns the static icon for this entity type.
     *
     * @return array{name: string, namespace: string, prefix: string}|string
     */
    public static function getStaticIcon(): string|array;
}
