<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use ArgumentCountError;
use BadMethodCallException;
use BootstrapUI\View\Helper\HtmlHelper as BootstrapUIHtmlHelper;
use Cake\View\View;
use InvalidArgumentException;
use Override;

/**
 * {@inheritDoc}
 *
 * @method string code(string $text = '', array $options = [])
 * @method string em(string $text = '', array $options = [])
 * @method string kbd(string $text = '', array $options = [])
 * @method string h1(string $text = '', array $options = [])
 * @method string h2(string $text = '', array $options = [])
 * @method string h3(string $text = '', array $options = [])
 * @method string h4(string $text = '', array $options = [])
 * @method string h5(string $text = '', array $options = [])
 * @method string h6(string $text = '', array $options = [])
 * @method string pre(string $text = '', array $options = [])
 * @method string small(string $text = '', array $options = [])
 * @method string span(string $text = '', array $options = [])
 * @method string strong(string $text = '', array $options = [])
 * @method string time(string $text = '', array $options = [])
 * @method string title(string $text = '', array $options = [])
 * @method string underline(string $text = '', array $options = [])
 */
class HtmlHelper extends BootstrapUIHtmlHelper
{
    /**
     * @inheritDoc
     */
    public function __construct(View $View, array $config = [])
    {
        if (!isset($config['iconDefaults'])) {
            $config['iconDefaults'] = [
                'namespace' => 'fas',
                'prefix' => 'fa',
            ];
        }

        parent::__construct(View: $View, config: $config);
    }

    /**
     * Magic "call" method.
     *
     * It provides the methods in the class description, for example:
     *
     * ```
     * $this->Html->h1('My text');
     * ```
     *
     * @param string $tag Method name, it will be the tag name
     * @param array $arguments Other arguments
     * @return string
     * @throws \ArgumentCountError If there are too many or too few arguments
     */
    public function __call(string $tag, array $arguments): string
    {
        $counter = count($arguments);
        if ($counter < 1) {
            throw new ArgumentCountError(sprintf('Too few arguments for `%s::%s()`, %s passed and at least 1 expected', $this::class, $tag, $counter));
        }
        if ($counter > 2) {
            throw new ArgumentCountError(sprintf('Too many arguments for `%s::%s()`, %s passed and at most 2 expected', $this::class, $tag, $counter));
        }

        $text = $arguments['text'] ?? ($arguments[0] ?? '');
        $options = $arguments['options'] ?? ($arguments[1] ?? []);

        return $this->tag(name: match ($tag) {
            'underline' => 'u',
            default => $tag,
        }, text: $text, options: $options);
    }

    /**
     * Adds icons to `$title`, starting from the options.
     *
     * Then it manipulates the options, even removing values that are no longer needed (because they were tied to the
     * icon). Finally, it returns an array with the title and the (manipulated) options.
     *
     * Example:
     * ```
     * [$title, $options] = $this->addIconToTitle($title, $options);
     * ```
     *
     * @param string $title
     * @param array $options
     * @return array{string, array} Array with the title and the (manipulated) options
     * @throws \InvalidArgumentException On missing icon `name` value
     */
    public function addIconToTitle(string $title, array $options = []): array
    {
        $icon = $options['icon'] ?? null;
        unset($options['icon']);

        if (!$icon) {
            return [$title, $options];
        }

        if (is_string($icon)) {
            [$name, $iconOptions] = [$icon, []];
        } else {
            $name = $icon['name'] ?? null;
            if (!$name) {
                throw new InvalidArgumentException('Missing icon `name` value');
            }
            unset($icon['name']);
            //Remaining values, with `name` removed, are the options for the icon
            $iconOptions = $icon;
        }

        $options = $this->addClass(options: $options, class: 'text-decoration-none');

        return [
            $this->icon(name: $name, options: $iconOptions) . ' ' . $title,
            $options,
        ];
    }

    /**
     * Adds tooltip, starting from the options.
     *
     * Takes the options and, if the `tooltip` option is present (as a string or array of strings),
     *  constructs all the options needed for the tooltip, then returns the options.
     *
     * @param array $options
     * @return array
     * @link https://getbootstrap.com/docs/5.3/components/tooltips
     */
    public function addTooltip(array $options): array
    {
        if (!isset($options['tooltip'])) {
            return $options;
        }

        $tooltip = implode('<br />', (array)$options['tooltip']);
        unset($options['tooltip']);

        return [
            'data-bs-html' => 'true',
            'data-bs-title' => $tooltip,
            'data-bs-toggle' => 'tooltip',
        ] + $options;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \BadMethodCallException With `$title` as array
     */
    #[Override]
    public function link(string|array $title, array|string|null $url = null, array $options = []): string
    {
        if (is_array($title)) {
            throw new BadMethodCallException('`$title` as array is not supported');
        }

        $options += [
            'escape' => false,
        ];

        [$title, $options] = $this->addIconToTitle(title: $title, options: $options);

        return parent::link(title: $title, url: $url, options: $options);
    }
}
