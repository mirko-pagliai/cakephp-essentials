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
     * ```
     * $title = $this->addIconToTitle($title, $options);
     * unset($options['icon']);
     * ```
     *
     * @param string $title
     * @param array $options
     * @return string
     * @throws \InvalidArgumentException On missing icon `name` value
     */
    public function addIconToTitle(string $title, array $options = []): string
    {
        if (empty($options['icon'])) {
            return $title;
        }

        if (is_string($options['icon'])) {
            return $this->icon(name: $options['icon']) . ' ' . $title;
        }

        $name = $options['icon']['name'];
        if (!$name) {
            throw new InvalidArgumentException('Missing icon `name` value');
        }
        unset($options['icon']['name']);

        return $this->icon(name: $name, options: $options['icon']) . ' ' . $title;
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

        $title = $this->addIconToTitle(title: $title, options: $options);
        unset($options['icon']);

        return parent::link(title: $title, url: $url, options: $options);
    }
}
