<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use ArgumentCountError;
use BadMethodCallException;
use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * AlertHelper.
 *
 * Allows you to create alerts and supports icon alerts.
 *
 * Provides generic `alert()` method and `link()` and `linkPath()` methods to generate alert-compatible links.
 *
 * Using the `__call()` method, provides all supported alert types as methods (`success()`, `danger()`, and so on).
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
 *
 * @see https://getbootstrap.com/docs/5.3/components/alerts
 */
class AlertHelper extends Helper
{
    use StringTemplateTrait;

    /**
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        // Default icons for some alert types
        'icon' => [
            'danger' => HtmlHelper::WARNING_ICON_FILL,
            'success' => HtmlHelper::SUCCESS_ICON,
            'warning' => HtmlHelper::WARNING_ICON_FILL,
        ],
        'templates' => [
            // Wrappers used when an icon is also used to separate it from the text
            'wrapperIconAndText' => '<div class="alert-icon me-2">{{icon}}</div><div class="alert-text">{{text}}</div>',
        ],
    ];

    /**
     * @var array<string>
     */
    protected array $helpers = ['Html'];

    /**
     * Magic method
     *
     * Provides all supported alert types as methods (`success()`, `danger()`, and so on).
     *
     * @param string $name Method name. It will be the `$type` argument
     * @param array $arguments Params, other arguments
     * @return string
     * @throws \ArgumentCountError with too many or too few arguments
     * @throws \BadMethodCallException with no existing method
     */
    public function __call(string $name, array $arguments): string
    {
        if (!in_array(needle: $name, haystack: ['danger', 'dark', 'info', 'light', 'primary', 'secondary', 'success', 'warning'])) {
            throw new BadMethodCallException(sprintf('Method `%s::%s()` does not exist.', $this::class, $name));
        }

        if (count($arguments) > 2) {
            throw new ArgumentCountError(sprintf(
                'Too many arguments for `%s::%s()`, %s passed and at most 2 expected.',
                $this::class,
                $name,
                count($arguments),
            ));
        }

        return $this->alert($name, ...$arguments);
    }

    /**
     * Creates an alert.
     *
     * @param string $type Alert type (`primary`, `secondary` and so on)
     * @param array<string>|string $text Alert text, as a string or array of strings
     * @param array<string, mixed> $options Array of options and HTML attributes
     * @return string
     */
    public function alert(string $type, string|array $text, array $options = []): string
    {
        $options += [
            'icon' => $this->getConfig('icon.' . $type),
            'role' => 'alert',
        ];

        if (is_array($text)) {
            $text = implode('<br />', $text);
        }

        //Adds some classes to the wrapper
        $options = $this->addClass(options: $options, class: 'alert alert-' . $type . ' border-0');

        if ($options['icon']) {
            //Adds other some classes to wrapper
            $options = $this->addClass(options: $options, class: 'd-flex align-items-baseline');

            $text = $this->formatTemplate(name: 'wrapperIconAndText', data: compact('text') + [
                'icon' => $this->Html->buildIcon($options),
            ]);
        }
        unset($options['icon']);

        return $this->Html->div(text: $text, options: $options);
    }

    /**
     * Creates an HTML link, compatible with alerts (adds the `alert-link` class).
     *
     * @param array|string $title
     * @param array|string|null $url
     * @param array<string, mixed> $options
     * @return string
     *
     * @see \Cake\View\Helper\HtmlHelper::link() for more information about the method arguments
     * @see https://getbootstrap.com/docs/5.3/components/alerts/#link-color
     */
    public function link(array|string $title, array|string|null $url = null, array $options = []): string
    {
        $options = $this->addClass(options: $options, class: 'alert-link');

        return $this->Html->link(title: $title, url: $url, options: $options);
    }

    /**
     * Creates an HTML link from a route path string, compatible with alerts (adds the `alert-link` class).
     *
     * @param string $title
     * @param string $path
     * @param array $params
     * @param array<string, mixed> $options
     * @return string
     *
     * @see \Cake\View\Helper\HtmlHelper::linkFromPath() for more information about the method arguments
     * @see https://getbootstrap.com/docs/5.3/components/alerts/#link-color
     */
    public function linkFromPath(string $title, string $path, array $params = [], array $options = []): string
    {
        return $this->link(title: $title, url: ['_path' => $path] + $params, options: $options);
    }
}
