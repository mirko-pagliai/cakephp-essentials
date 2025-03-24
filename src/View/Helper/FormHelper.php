<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use BootstrapUI\View\Helper\FormHelper as BootstrapUIFormHelper;
use Override;
use function Cake\I18n\__d as __d;

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

    /**
     * @inheritDoc
     */
    #[Override]
    public function select(string $fieldName, iterable $options = [], array $attributes = []): string
    {
        /**
         * If the `default` and `value` attributes are empty, set the default `empty` attribute.
         *
         * Set `empty` to `false` or `null` to disable it.
         */
        if (empty($attributes['default']) && empty($attributes['value']) && empty($attributes['multiple'])) {
            $attributes += ['empty' => '-- ' . __d('cakephp/essentials', 'select an option') . ' --'];
        }

        return parent::select(fieldName: $fieldName, options: $options, attributes: $attributes);
    }
}
