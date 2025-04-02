<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use ArgumentCountError;
use BadMethodCallException;
use BootstrapUI\View\Helper\HtmlHelper as BootstrapUIHtmlHelper;
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
 * @method string var(string $text = '', array $options = [])
 */
class HtmlHelper extends BootstrapUIHtmlHelper
{
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

        $tag = match ($tag) {
            'underline' => 'u',
            default => $tag,
        };
        $text = $arguments['text'] ?? ($arguments[0] ?? '');
        $options = $arguments['options'] ?? ($arguments[1] ?? []);

        return $this->tag(name: $tag, text: $text, options: $options);
    }

    /**
     * Builds and returns an icon, starting from `$options`.
     *
     * For example:
     * ```
     * $this->Html->buildIcon(['icon' => 'house'])
     * ```
     * will return:
     * ```
     * <i class="bi bi-house"></i>
     * ```
     *
     * and:
     * ```
     * $this->Html->buildIcon(['icon' => ['name' => 'house', 'class' => 'fs-5']])
     * ```
     * will return:
     * ```
     * <i class="fs-5 bi bi-house"></i>
     * ```
     *
     * @param array $options
     * @return string
     * @throws \InvalidArgumentException On missing icon `name` value
     */
    public function buildIcon(array $options): string
    {
        if (empty($options['icon'])) {
            return '';
        }

        if (is_string($options['icon'])) {
            return $this->icon(name: $options['icon']);
        }

        if (!isset($options['icon']['name'])) {
            throw new InvalidArgumentException('Missing icon `name` value');
        }

        //Remaining values, with `name` removed, are the options for the icon
        $name = $options['icon']['name'];
        unset($options['icon']['name']);

        return $this->icon(name: $name, options: $options['icon']);
    }

    /**
     * Builds and adds icon to `$title`, starting from `$options`.
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
     * @return array{string, array} Array with title and icon as string and the (manipulated) options
     */
    public function addIconToTitle(string $title = '', array $options = []): array
    {
        $icon = $this->buildIcon($options);
        unset($options['icon']);

        if (!$title) {
            return [$icon, $options];
        }

        return [$icon ? $icon . ' ' . $title : $title, $options];
    }

    /**
     * Adds a popover, starting from the options.
     *
     * Takes the options and, if the `popover` option is present, constructs all the options needed for
     *  the popover, then returns the options.
     *
     * HTML is enabled for popover content.
     *
     * We suggest you set the `onclick` option as `event.preventDefault();` if you use it on a link tag
     *  that doesn't work properly as a link.
     *
     * @param array $options
     * @return array
     *
     * @link https://getbootstrap.com/docs/5.3/components/popovers
     */
    public function addPopover(array $options): array
    {
        if (empty($options['popover'])) {
            return $options;
        }

        $popover = implode('<br />', (array)$options['popover']);
        unset($options['popover']);

        return [
            'data-bs-content' => $popover,
            'data-bs-html' => 'true',
            'data-bs-trigger' => 'focus',
            'data-bs-toggle' => 'popover',
            'role' => 'button',
            'tabindex' => 0,
        ] + $options;
    }

    /**
     * Adds a tooltip, starting from the options.
     *
     * Takes the options and, if the `tooltip` option is present (as a string or array of strings),
     *  constructs all the options needed for the tooltip, then returns the options.
     *
     * @param array $options
     * @return array
     *
     * @link https://getbootstrap.com/docs/5.3/components/tooltips
     */
    public function addTooltip(array $options): array
    {
        if (empty($options['tooltip'])) {
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
     * Creates a formatted ABBR element.
     *
     * @param string $text Text
     * @param string $title Title
     * @param array<string, mixed> $options Array of HTML attributes
     * @return string
     *
     * @see https://getbootstrap.com/docs/5.3/content/typography/#abbreviations
     */
    public function abbr(string $text, string $title = '', array $options = []): string
    {
        $options += [
            'class' => 'initialism',
        ];

        if ($title) {
            $options['title'] = $title;
        }

        return $this->tag(name: 'abbr', text: $text, options: $options);
    }

    /**
     * Creates a `button` tag.
     *
     * @param string $text
     * @param array $options
     * @return string
     *
     * @see \Cake\Essentials\View\Helper\HtmlHelper::tag() for all options
     */
    public function button(string $text = '', array $options = []): string
    {
        $options += [
            'type' => 'button',
        ];

        $options = $this->injectClasses(classes: 'btn', options: $options);

        return $this->tag(name: 'button', text: $text, options: $options);
    }

    /**
     * Creates a "flush" list using `div` elements (for the wrapper and for each item), instead of the `ol/ul`
     *  and the `li` tags.
     *
     * For example:
     * ```
     * $this->Html->flushDiv(list: [
     *    'First string',
     *    'Second string',
     * ]);
     * ```
     * will return:
     * ```
     * <div class="list-group list-group-flush">
     *    <div class="list-group-item">First string</div>
     *    <div class="list-group-item">Second string</div>
     * </div>
     * ```
     *
     * @param iterable<string> $list List items (each item will be wrapped in a `div` and `$itemOptions` will be applied)
     * @param array<string, mixed> $options Options and additional HTML attributes for the wrapper
     * @param array<string, mixed> $itemOptions Options and additional HTML attributes for each item
     * @return string
     *
     * @see https://getbootstrap.com/docs/5.3/components/list-group/#flush
     */
    public function flushDiv(iterable $list, array $options = [], array $itemOptions = []): string
    {
        $out = '';
        $itemOptions = $this->addClass(options: $itemOptions, class: 'list-group-item');
        foreach ($list as $item) {
            $out .= $this->div(text: $item, options: $itemOptions);
        }

        $options = $this->addClass(options: $options, class: 'list-group list-group-flush');

        return $this->div(text: $out, options: $options);
    }

    /**
     * {@inheritDoc}
     *
     * Unlike the original method, it sets some default options and supports tooltips.
     *
     * Unlike other methods, the `img-fluid` class here is not appended to other possible existing classes,
     *  but only used as a default class.
     *
     * The `alt` attribute is, by default, auto-determined from `$path`. Set it to `false` to disable it.
     */
    #[Override]
    public function image(array|string $path, array $options = []): string
    {
        $options += [
            'class' => 'img-fluid',
        ];

        if (!isset($options['alt']) && is_string($path)) {
            $parseUrl = parse_url($path);
            $options['alt'] = isset($parseUrl['path']) ? basename($parseUrl['path']) : false;
        }

        $options = $this->addTooltip($options);

        return parent::image(path: $path, options: $options);
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
            'title' => trim(strip_tags($title)) ?: null,
        ];

        if (!empty($options['icon'])) {
            $options = $this->addClass(options: $options, class: 'text-decoration-none');
        }
        [$title, $options] = $this->addIconToTitle(title: $title, options: $options);
        $options = $this->addPopover($options);
        $options = $this->addTooltip($options);

        return parent::link(title: $title, url: $url, options: $options);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function tag(string $name, ?string $text = null, array $options = []): string
    {
        [$text, $options] = $this->addIconToTitle(title: is_null($text) ? '' : $text, options: $options);
        $options = $this->addPopover(options: $options);
        $options = $this->addTooltip(options: $options);

        return parent::tag($name, $text, $options);
    }
}
