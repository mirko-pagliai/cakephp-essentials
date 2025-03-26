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
            'escapeTitle' => false,
        ];

        [$title, $options] = $this->Html->addIconToTitle(title: $title, options: $options);

        return parent::button(title: $title, options: $options);
    }

    /**
     * {@inheritDoc}
     *
     * Compared to the original method, this:
     * - Adds `switch` type.
     * - Adds support for `appendNowButton` boolean option.
     * - Adds support for `help` option (also) as string array.
     */
    #[Override]
    public function control(string $fieldName, array $options = []): string
    {
        $options += [
            'appendNowButton' => false,
            'templates' => [],
            'type' => null,
        ];

        /**
         * Support for "switch" type.
         *
         * @see https://github.com/FriendsOfCake/bootstrap-ui?tab=readme-ov-file#switches
         * @see https://getbootstrap.com/docs/5.3/forms/checks-radios/#switches
         */
        if ($options['type'] === 'switch') {
            $options = ['type' => 'checkbox', 'switch' => true] + $options;
        }

        /**
         * Support for "appendNowButton" option.
         *
         * When the `appendNowButton` option is `true`, appends a "now" button that automatically sets the input value to the
         *  current date and time.
         *
         * It requires the `Moment.js` library.
         */
        if ($options['appendNowButton']) {
            $options['append'] = $this->Html->button(text: __d('cakephp/essentials', 'Now'), options: [
                'class' => 'btn btn-primary btn-sm text-nowrap',
                'icon' => 'clock',
                'onclick' => 'javascript:event.preventDefault(); $(this).prev(\'input\').val(moment(new Date()).format(\'YYYY-MM-DDTHH:mm\'))',
            ]);

            /** @phpstan-ignore assignOp.invalid */
            $options['templates'] += [
                'inputGroupContainer' => '<div{{attrs}}><div class="d-flex gap-2">{{prepend}}{{content}}{{append}}</div></div>',
            ];
        }
        unset($options['appendNowButton']);

        /**
         * This allows the `help` option to be an array
         */
        if (!empty($options['help']) && is_array($options['help'])) {
            $options['help'] = implode(separator: '', array: array_map(
                /** @phpstan-ignore argument.type */
                callback: fn (string $help): string => '<div>' . $help . '</div>',
                array: $options['help']
            ));
        }

        return parent::control(fieldName: $fieldName, options: $options);
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
        if (!empty($options['icon'])) {
            $options = $this->addClass(options: $options, class: 'text-decoration-none');
        }
        [$title, $options] = $this->Html->addIconToTitle($title, $options);

        return parent::postLink($title, $url, $options);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function radio(string $fieldName, iterable $options = [], array $attributes = []): string
    {
        $attributes += ['escape' => false];

        return parent::radio(fieldName: $fieldName, options: $options, attributes: $attributes);
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

    /**
     * @inheritDoc
     */
    #[Override]
    public function submit(?string $caption = null, array $options = []): string
    {
        $options += ['class' => 'btn btn-success'];

        return parent::submit($caption, $options);
    }
}
