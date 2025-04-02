<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use BadMethodCallException;
use Cake\View\Helper;

/**
 * AlertHelper.
 *
 * @property \Cake\Essentials\View\Helper\HtmlHelper $Html
 *
 * @method string danger(string|array $text, array $options = [])
 * @method string dark(string|array $text, array $options = [])
 * @method string info(string|array $text, array $options = [])
 * @method string light(string|array $text, array $options = [])
 * @method string primary(string|array $text, array $options = [])
 * @method string secondary(string|array $text, array $options = [])
 * @method string success(string|array $text, array $options = [])
 * @method string warning(string|array $text, array $options = [])
 */
class AlertHelper extends Helper
{
    /**
     * @var array<string>
     */
    protected array $helpers = ['Html'];

    public function __call(string $name, array $arguments): string
    {
        if (in_array(needle: $name, haystack: ['danger', 'dark', 'info', 'light', 'primary', 'secondary', 'success', 'warning'])) {
            return '';
        }

        throw new BadMethodCallException('Method `' . $this::class . '::' . $name . '()` does not exist.');
    }

    /**
     * Creates an HTML link, compatible with alerts (adds the `alert-link` class).
     *
     * @param array|string $title
     * @param array|string|null $url
     * @param array<string, mixed> $options
     * @return string
     *
     * @see \Cake\Essentials\View\Helper\HtmlHelper::link() for more information about the method arguments
     * @see https://getbootstrap.com/docs/5.3/components/alerts/#link-color
     */
    public function link(array|string $title, array|string|null $url = null, array $options = []): string
    {
        $options = $this->addClass(options: $options, class: 'alert-link');

        return $this->Html->link(title: $title, url: $url, options: $options);
    }

    /**
     * Creates an HTML link from route path string, compatible with alerts (adds the `alert-link` class).
     *
     * @param string $title
     * @param string $path
     * @param array $params
     * @param array<string, mixed> $options
     * @return string
     *
     * @see \Cake\Essentials\View\Helper\HtmlHelper::linkPath() for more information about the method arguments
     * @see https://getbootstrap.com/docs/5.3/components/alerts/#link-color
     */
    public function linkFromPath(string $title, string $path, array $params = [], array $options = []): string
    {
        return $this->link(title: $title, url: ['_path' => $path] + $params, options: $options);
    }
}
