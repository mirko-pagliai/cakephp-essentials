<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use Cake\View\Helper;

/**
 * A helper that provides methods to "beautify" the output of some specific entities and values.
 *
 * @property \Cake\Essentials\View\Helper\HtmlHelper $Html
 */
class BeautifierHelper extends Helper
{
    /**
     * @var array<string>
     */
    protected array $helpers = ['Html'];

    /**
     * @param array $options
     * @return string
     */
    protected function _questionPopoverAndTooltip(array $options = []): string
    {
        $options += [
            'class' => null,
            'icon' => ['name' => 'question-circle-fill', 'class' => 'opacity-75 text-body-tertiary'],
        ];

        $options = $this->Html->addClass(options: $options, class: 'cursor-pointer');

        return $this->Html->span(options: $options);
    }

    /**
     * Returns a sort of badge based on the `question-circle-fill` icon ("?"), complete with popover.
     *
     * @param array<string>|string $popover Popover text, as string or array of string
     * @param array $options
     * @return string
     */
    public function questionPopover(string|array $popover, array $options = []): string
    {
        return $this->_questionPopoverAndTooltip(options: compact('popover') + $options);
    }

    /**
     * Returns a sort of badge based on the `question-circle-fill` icon ("?"), complete with tooltip.
     *
     * @param array<string>|string $tooltip Tooltip text, as string or array of string
     * @param array $options
     * @return string
     */
    public function questionTooltip(string|array $tooltip, array $options = []): string
    {
        return $this->_questionPopoverAndTooltip(options: compact('tooltip') + $options);
    }
}
