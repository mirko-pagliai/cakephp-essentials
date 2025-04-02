<?php
declare(strict_types=1);

namespace Cake\Essentials\View\Helper;

use Cake\View\Helper\TextHelper as CakeTextHelper;
use Override;

/**
 * @inheritDoc
 */
class TextHelper extends CakeTextHelper
{
    /**
     * Extends and improves the "autoParagraph" function.
     *
     * For example, this:
     * ```
     * This is a text
     * This is another text
     * ```
     *
     * becomes this:
     * ```
     * <p class="mb-2">This is a text</p>
     * <p class="mb-0">This is another text</p>
     * ```
     *
     * It does not apply margins to the last line (even when it is a single line).
     * It automatically `trim()`s the lines.
     *
     * @param string|null $text
     * @return string
     */
    #[Override]
    public function autoParagraph(?string $text): string
    {
        if (is_null($text) || !trim($text)) {
            return '';
        }
        if (!str_contains($text, PHP_EOL)) {
            return '<p class="mb-0">' . trim($text) . '</p>';
        }

        $explodedText = array_filter(explode(PHP_EOL, $text));
        $lastElement = end($explodedText);

        foreach ($explodedText as $k => $sText) {
            $explodedText[$k] = ($sText == $lastElement ? '<p class="mb-0">' : '<p class="mb-2">') . trim($sText) . '</p>';
        }

        return implode(PHP_EOL, $explodedText);
    }

    /**
     * Highlights a given phrase in a text.
     *
     * @param string $text Text to search the phrase in
     * @param array<string>|string $phrase The phrase or phrases that will be searched
     * @param array<string, mixed> $options An array of HTML attributes and options
     * @return string The highlighted text
     *
     * @see \Cake\Utility\Text::highlight() for all options
     */
    public function highlight(string $text, array|string $phrase, array $options = []): string
    {
        $options += ['format' => '<span class="text-bg-warning">\1</span>'];

        return parent::highlight(text: $text, phrase: $phrase, options: $options);
    }
}
