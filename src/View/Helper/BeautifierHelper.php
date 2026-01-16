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
     * Returns a nice representation of an IP address.
     *
     * @param string $ipAddress The IP address to be displayed.
     * @param array<string, mixed> $options Additional options for customizing the display, including icon configuration.
     * @return string The rendered HTML string for the IP address.
     */
    public function ipAddress(string $ipAddress, array $options = []): string
    {
        $options += [
            'icon' => ['name' => 'globe-americas', 'class' => 'small text-body-secondary'],
        ];

        return $this->Html->span($this->Html->code($ipAddress), $options);
    }

    /**
     * @param array<string, mixed> $options
     * @return string
     */
    protected function _questionPopoverAndTooltip(array $options = []): string
    {
        $options += [
            'class' => null,
            'icon' => ['name' => 'question-circle-fill', 'class' => 'opacity-75 text-body-tertiary'],
        ];

        return $this->Html->span(options: $options);
    }

    /**
     * Returns a sort of badge based on the `question-circle-fill` icon ("?"), complete with popover.
     *
     * @param array<string>|string $popover Popover text, as string or array of string
     * @param array<string, mixed> $options
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
     * @param array<string, mixed> $options
     * @return string
     */
    public function questionTooltip(string|array $tooltip, array $options = []): string
    {
        return $this->_questionPopoverAndTooltip(options: compact('tooltip') + $options);
    }

    /**
     * Returns the user agent, wrapped in a `code` tag and with icon.
     *
     * @param string $userAgent The user agent string to parse and identify.
     * @param array<string, mixed> $options Additional options to customize the output.
     * @return string The formatted HTML span element representing the user agent.
     */
    public function userAgent(string $userAgent, array $options = []): string
    {
        if (str_contains($userAgent, 'Android')) {
            $icon = 'android';
        } elseif (preg_match('/(Linux|Windows|iPhone|Mac OS)/', $userAgent, $matches) === 1) {
            $icon = match ($matches[0]) {
                'Linux' => 'ubuntu',
                'iPhone', 'Mac OS' => 'apple',
                default => lcfirst($matches[0]),
            };
        } else {
            //Icon for unknown devices
            $icon = 'question-circle-fill';
        }

        $options += [
            'icon' => ['name' => $icon, 'class' => 'me-1 small text-body-secondary'],
        ];

        return $this->Html->span($this->Html->code($userAgent), options: $options);
    }
}
