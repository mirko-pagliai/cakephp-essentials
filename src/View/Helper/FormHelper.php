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
    protected array $helpers = [
        'Cake/Essentials.Html',
        'Url',
    ];

    /**
     * @inheritDoc
     */
    public function postLink(string $title, array|string|null $url = null, array $options = []): string
    {
        $title = $this->Html->addIconToTitle($title, $options);
        unset($options['icon']);

        return parent::postLink($title, $url, $options);
    }
}
