<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use BootstrapUI\View\Helper\HtmlHelper as BootstrapUIHtmlHelper;

/**
 * @inheritDoc
 */
class HtmlHelper extends BootstrapUIHtmlHelper
{
    /**
     * @inheritDoc
     */
    public function link(string|array $title, array|string|null $url = null, array $options = []): string
    {
        $options += [
            'escape' => false,
            'icon' => null,
        ];

        if (is_string($title) && $options['icon']) {
            /** @var string $icon */
            $icon = $options['icon'];
            $title = $this->icon($icon) . ' ' . $title;
        }
        unset($options['icon']);

        return parent::link($title, $url, $options);
    }
}
