<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use BadMethodCallException;
use Cake\View\Helper;

/**
 * CollapsibleHelper.
 *
 * This helper allows you to create "collapsible" blocks.
 *
 * It provides two methods, `link()` and `content()` that allow you to create custom blocks.
 * You need to be careful to call these methods consecutively and then, if you want it, to
 * bundle the output in a wrapper.
 *
 * Remember that to use "toggle" icon you need `webroot/js/collapsible-toggle-icon.min.js`,
 * in addition to jQuery.
 *
 * ```
 * echo $this->Html->script('/cake/essentials/js/collapsible-toggle-icon.js');
 * ```
 *
 * @property \Cake\Essentials\View\Helper\HtmlHelper $Html
 *
 * @uses webroot/js/collapsible-toggle-icon.min.js
 */
class CollapsibleHelper extends Helper
{
    /**
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        /**
         * "Toggle" icons, that will be appended to the link and allow to open/close the collapsible.
         *
         * Not to be confused with the link icon set using the `$options` argument.
         *
         * If you want to disable toggle icons completely:
         * ```
         * $this->Collapsible->setConfig('toggleIcon', false);
         * ```
         */
        'toggleIcon' => [
            'open' => ['name' => 'chevron-up', 'class' => 'ms-1'],
            'close' => ['name' => 'chevron-down', 'class' => 'ms-1'],
        ],
    ];

    public ?bool $alreadyOpen = null;

    public ?string $collapsibleId = null;

    /**
     * @var array
     */
    protected array $helpers = ['Html'];

    /**
     * Creates the link that allow to open/close the collapsible.
     *
     * It is essential that this method sets the `$alreadyOpen` and `$collapsibleId` properties, so you can subsequently
     *  call the `content()` method (which will use the same properties).
     *
     * @param string $title
     * @param string $collapsibleId
     * @param array $options
     * @param bool $alreadyOpen
     * @return string
     */
    public function link(string $title, string $collapsibleId = '', array $options = [], bool $alreadyOpen = false): string
    {
        $this->alreadyOpen = $alreadyOpen;
        $this->collapsibleId = $collapsibleId ?: uniqid('collapsible-');

        $options += [
            'role' => 'button',
        ];

        /**
         * Sets toggle icons.
         */
        $openIconArgs = $this->getConfig('toggleIcon.open');
        $closeIconArgs = $this->getConfig('toggleIcon.close');
        if ($openIconArgs && $closeIconArgs) {
            $openIcon = $this->Html->buildIcon(['icon' => $openIconArgs]);
            $closeIcon = $this->Html->buildIcon(['icon' => $closeIconArgs]);

            // Updates title, added some classes and adds the `onclick` attribute
            $title .= '<span class="toggle-icon">' . ($this->alreadyOpen ? $openIcon : $closeIcon) . '</span>';
            $options = $this->addClass(options: $options, class: 'text-decoration-none');

            $options['data-open-icon'] = htmlentities($openIcon);
            $options['data-close-icon'] = htmlentities($closeIcon);
        }

        return $this->Html->link(
            title: $title,
            url: '#' . $this->collapsibleId,
            options: [
                'aria-controls' => $this->collapsibleId,
                'aria-expanded' => $this->alreadyOpen ? 'true' : 'false',
                'data-bs-toggle' => 'collapse',
            ] + $options
        );
    }

    /**
     * Creates the contents of the collapse block.
     *
     * You must first have correctly called the `link()` method, which sets the necessary properties.
     *
     * @param array<string>|string $content
     * @param array $options
     * @return string
     * @throws \BadMethodCallException if the `link()` method has not been called previously
     */
    public function content(string|array $content, array $options = []): string
    {
        if (!isset($this->alreadyOpen) || !isset($this->collapsibleId)) {
            throw new BadMethodCallException(
                'Seems that the link to open the collapsible was not set, perhaps the `link()` method was not called?'
            );
        }

        if (is_array($content)) {
            $content = implode('<br />', $content);
        }

        $options = $this->addClass(options: $options, class: $this->alreadyOpen ? 'collapse show' : 'collapse');

        $result = $this->Html->div(text: $content, options: ['id' => $this->collapsibleId] + $options);

        // Reset properties. This prevents consecutive method calls
        $this->alreadyOpen = $this->collapsibleId = null;

        return $result;
    }
}
