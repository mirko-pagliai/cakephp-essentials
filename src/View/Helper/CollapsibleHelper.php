<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use BadMethodCallException;
use Cake\View\Helper;

/**
 * CollapsibleHelper.
 *
 * @property \Cake\Essentials\View\Helper\HtmlHelper $Html
 */
class CollapsibleHelper extends Helper
{
    public bool $alreadyOpen;

    public string $collapsibleId;

    /**
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        /**
         * Icons that will be appended to the link and will allow you to open/close the collapsible
         *
         * Not to be confused with the link icon set using the `$options` argument.
         */
        'icon' => [
            'open' => ['name' => 'chevron-up', 'class' => 'ms-1'],
            'close' => ['name' => 'chevron-down', 'class' => 'ms-1'],
        ],
    ];

    /**
     * @var array<string>
     */
    protected array $helpers = ['Html'];

    public function link(string $title, string $collapsibleId = '', array $options = [], bool $alreadyOpen = false): string
    {
        $this->alreadyOpen = $alreadyOpen;
        $this->collapsibleId = $collapsibleId ?: uniqid('collapsible-');

        /**
         * Builds open and close icons.
         *
         * These will then be handled via the `onclick` options that will use javascript to replace the icon.
         */
        $openIcon = $this->Html->buildIcon(['icon' => $this->getConfigOrFail('icon.open')]);
        $closeIcon = $this->Html->buildIcon(['icon' => $this->getConfigOrFail('icon.close')]);

        $options += [
            'role' => 'button',
        ];

        $options = $this->addClass(options: $options, class: 'text-decoration-none');

        return $this->Html->link(
            title: $title . ($this->alreadyOpen ? $openIcon : $closeIcon),
            url: '#' . $this->collapsibleId,
            options: [
                'aria-controls' => $this->collapsibleId,
                'aria-expanded' => $this->alreadyOpen ? 'true' : 'false',
                'data-bs-toggle' => 'collapse',
                'onclick' => 'javascript:$(\'i\', this).replaceWith($(this).hasClass(\'collapsed\') ? \'' . htmlentities($closeIcon) . '\' : \'' . htmlentities($openIcon) . '\')',
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
     */
    public function content(string|array $content, array $options = []): string
    {
        if (!isset($this->alreadyOpen) || !isset($this->collapsibleId)) {
            throw new BadMethodCallException('Seems that the link to open the collapsible was not set, perhaps the `link()` method was not called?');
        }

        if (is_array($content)) {
            $content = implode('<br />', $content);
        }

        $options = $this->addClass(options: $options, class: $this->alreadyOpen ? 'collapse show' : 'collapse');

        return $this->Html->div(text: $content, options: ['id' => $this->collapsibleId] + $options);
    }
}
