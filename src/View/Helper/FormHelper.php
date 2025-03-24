<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use BootstrapUI\View\Helper\FormHelper as BootstrapUIFormHelper;
use Override;

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
    #[Override]
    public function button(string $title, array $options = []): string
    {
        $options += [
            'class' => isset($options['type']) && $options['type'] === 'submit' ? 'btn btn-success' : 'btn btn-primary',
        ];

        return parent::button(title: $title, options: $options);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function dateTime(string $fieldName, array $options = []): string
    {
        $options += ['step' => '60'];

        return parent::dateTime(fieldName: $fieldName, options: $options);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function postLink(string $title, array|string|null $url = null, array $options = []): string
    {
        [$title, $options] = $this->Html->addIconToTitle($title, $options);

        return parent::postLink($title, $url, $options);
    }
}
