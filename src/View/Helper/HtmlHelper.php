<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use BadMethodCallException;
use BootstrapUI\View\Helper\HtmlHelper as BootstrapUIHtmlHelper;
use Cake\View\View;
use InvalidArgumentException;

/**
 * @inheritDoc
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
     * {@inheritDoc}
     *
     * @throws \BadMethodCallException With `$title` as array
     */
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
