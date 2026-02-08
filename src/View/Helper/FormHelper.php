<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use BootstrapUI\View\Helper\FormHelper as BootstrapUIFormHelper;
use Cake\I18n\Date;
use Cake\I18n\DateTime;
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
     *
     * - adds support for the `datetime` type with the `default` option as the `now` string;
     * - adds `switch` type.
     * - adds support for the `date`/`datetime` types with the `appendNowButton` boolean option;
     * - adds support for `help` option (also) as string array.
     */
    #[Override]
    public function control(string $fieldName, array $options = []): string
    {
        $options += [
            'appendNowButton' => false,
            'default' => null,
            'templates' => [],
            'type' => null,
        ];

        $type = $options['type'] ?: $this->_inputType(fieldName: $fieldName, options: $options);

        if (in_array(needle: $type, haystack: ['date', 'datetime']) && $options['default'] === 'now') {
            /**
             * If type is `date`/`datetime` and if `default` is `now` string, correctly set to the current
             *  date or (adjusting the seconds) datetime.
             */
            $options['default'] = $type === 'datetime' ? DateTime::now()->second(0) : Date::today();
        } elseif ($type === 'switch') {
            /**
             * Support for "switch" type.
             *
             * @see https://github.com/FriendsOfCake/bootstrap-ui?tab=readme-ov-file#switches
             * @see https://getbootstrap.com/docs/5.3/forms/checks-radios/#switches
             */
            $options = ['type' => 'checkbox', 'switch' => true] + $options;
        }

        /**
         * Support for "appendNowButton" option.
         *
         * When the `appendNowButton` option is `true`, appends a "now" button that automatically sets the input value
         *  to the current date and time for `datetime` type and to the current date for `date` type.
         *
         * It requires `current-local-datetime.min.js`.
         *
         * @link webroot/js/current-local-datetime.min.js
         */
        if (in_array(needle: $type, haystack: ['date', 'datetime']) && $options['appendNowButton']) {
            $text = __d('cake/essentials', 'Now');
            if ($type === 'date') {
                $text = __d('cake/essentials', 'Today');
            }

            $options['append'] = $this->Html->button(text: $text, options: [
                'class' => 'btn btn-primary btn-sm text-nowrap',
                'icon' => 'clock',
                'onclick' => 'event.preventDefault(); this.previousElementSibling.value = currentLocalDatetime();',
            ]);
        }
        unset($options['appendNowButton']);

        /**
         * This allows the `help` option to be an array
         */
        if (!empty($options['help']) && is_array($options['help'])) {
            $options['help'] = implode(separator: '', array: array_map(
                callback: fn(string $help): string => '<div>' . $help . '</div>',
                array: $options['help'],
            ));
        }

        /**
         * If the `append` option is present and if it is a string, if `append` contains a button and if the
         *  `inputGroupContainer` template has not been set, it automatically sets this template.
         */
        if (
            isset($options['append']) &&
            is_string($options['append']) &&
            str_starts_with(haystack: $options['append'], needle: '<button') &&
            !isset($options['templates']['inputGroupContainer'])
        ) {
            $options['templates'] += [
                'inputGroupContainer' => '<div{{attrs}}><div class="d-flex gap-2">{{prepend}}{{content}}{{append}}</div></div>',
            ];
        }

        return parent::control(fieldName: $fieldName, options: $options);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function deleteLink(string $title, array|string|null $url = null, array $options = []): string
    {
        $options += [
            'confirm' => __d('cake/essentials', 'Are you sure you want to delete this item?'),
        ];

        return parent::deleteLink(title: $title, url: $url, options: $options);
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
            $attributes += ['empty' => '-- ' . __d('cake/essentials', 'select an option') . ' --'];
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
