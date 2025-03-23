<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use BootstrapUI\View\Helper\FormHelper as BootstrapUIFormHelper;

/**
 * {@inheritDoc}
 *
 * @property \Cake\Essentials\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\UrlHelper $Url
 */
class FormHelper extends BootstrapUIFormHelper
{
    /**
     * @var array<string>
     */
    protected array $helpers = ['Html', 'Url'];

    /**
     * @inheritDoc
     */
    public function postLink(string $title, array|string|null $url = null, array $options = []): string
    {
        $options += [
            'icon' => null,
        ];

        if ($options['icon']) {
            $title = $this->Html->icon($options['icon']) . ' ' . $title;
        }
        unset($options['icon']);

        return parent::postLink($title, $url, $options);
    }
}
