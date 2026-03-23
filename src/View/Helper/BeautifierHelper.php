<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use Cake\I18n\Date;
use Cake\I18n\DateTime;
use Cake\View\Helper;
use Stringable;
use function Cake\I18n\__dx as __dx;

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
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        // Default icons
        'icon' => [
            'android' => 'android',
            'apple' => 'apple',
            'ipAddress' => 'globe-americas',
            'linux' => 'ubuntu',
            'question' => 'question-circle-fill',
            'time' => 'clock',
            'unknownDevice' => 'question-circle-fill',
            'windows' => 'windows',
        ],
    ];

    /**
     * Generates a string representation of a date range in the format "From {Start} to {End}".
     *
     * @param \Cake\I18n\Date|\Cake\I18n\DateTime $from The starting date or datetime
     * @param \Cake\I18n\Date|\Cake\I18n\DateTime $to The ending date or datetime
     * @param array<string, mixed> $options Additional formatting options. Supports 'format' for date formatting
     * @return string A formatted string representing the date range
     */
    public function fromDayToDay(Date|DateTime $from, Date|DateTime $to, array $options = []): string
    {
        /** @var string|int|null $format */
        $format = $options['format'] ?? 'dd/MM';
        unset($options['format']);

        return $this->time(
            text: __dx(
                'cake/essentials',
                'from day to day',
                'From {0} to {1}',
                $from->i18nFormat($format),
                $to->i18nFormat($format),
            ),
            options: $options,
        );
    }

    /**
     * Generates a formatted string representing the time range between two DateTime instances.
     *
     * @param \Cake\I18n\DateTime $from The starting time of the range
     * @param \Cake\I18n\DateTime $to The ending time of the range
     * @param array<string, mixed> $options Optional settings for output formatting. The `format` key defines the time format (default is 'HH:mm')
     * @return string A formatted string representing the time range
     */
    public function fromHourToHour(DateTime $from, DateTime $to, array $options = []): string
    {
        /** @var string|int|null $format */
        $format = $options['format'] ?? 'HH:mm';
        unset($options['format']);

        return $this->time(
            text: __dx(
                'cake/essentials',
                'from hour to hour',
                'From {0} to {1}',
                $from->i18nFormat($format),
                $to->i18nFormat($format),
            ),
            options: $options,
        );
    }

    /**
     * Returns a nice representation of an IP address.
     *
     * @param string $ipAddress The IP address to be displayed.
     * @param array<string, mixed> $options Additional options for customizing the display, including icon configuration.
     * @return string The rendered HTML string for the IP address.
     */
    public function ipAddress(string $ipAddress, array $options = []): string
    {
        if (!isset($options['icon'])) {
            $iconFromConfig = $this->getConfig('icon.ipAddress');
            $iconFromConfig = is_string($iconFromConfig) ? ['name' => $iconFromConfig] : (array)$iconFromConfig;

            $options['icon'] = $iconFromConfig + ['class' => 'small text-body-secondary'];
        }

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
        ];

        if (!isset($options['icon'])) {
            $iconFromConfig = $this->getConfig('icon.question');
            $iconFromConfig = is_string($iconFromConfig) ? ['name' => $iconFromConfig] : (array)$iconFromConfig;

            $options['icon'] = $iconFromConfig + ['class' => 'opacity-75 text-body-tertiary'];
        }

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
     * Returns a formatted time representation.
     *
     * @param \Stringable|string $text The time text or a stringable object representing time
     * @param array<string, mixed> $options Additional options for formatting
     * @return string The formatted time as a string
     */
    public function time(Stringable|string $text, array $options = []): string
    {
        $options += [
            'icon' => $this->getConfig('icon.time'),
        ];

        return $this->Html->time(text: (string)$text, options: $options);
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
        if (!isset($options['icon'])) {
            // The `Android` case must go first, so as not to conflict with `Linux; Android`
            if (str_contains($userAgent, 'Android')) {
                $iconFromConfig = $this->getConfig('icon.android');
            } elseif (preg_match('/(Linux|Windows|iPhone|Mac OS)/', $userAgent, $matches) === 1) {
                $iconFromConfig = match ($matches[0]) {
                    'iPhone', 'Mac OS' => $this->getConfig('icon.apple'),
                    default => $this->getConfig('icon.' . lcfirst($matches[0])),
                };
            } else {
                //Icon for unknown devices
                $iconFromConfig = $this->getConfig('icon.unknownDevice');
            }

            $iconFromConfig = is_string($iconFromConfig) ? ['name' => $iconFromConfig] : (array)$iconFromConfig;

            $options['icon'] = $iconFromConfig + ['class' => 'me-1 small text-body-secondary'];
        }

        return $this->Html->span($this->Html->code($userAgent), options: $options);
    }
}
