<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use Cake\Essentials\View\Helper\TextHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * TextHelperTest.
 */
#[CoversClass(TextHelper::class)]
class TextHelperTest extends TestCase
{
    protected TextHelper $Text;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->Text = new TextHelper(new View());
    }

    /**
     * @link \Cake\Essentials\View\Helper\TextHelper::autoParagraph()
     */
    #[Test]
    #[TestWith(['This is a text' . PHP_EOL . 'This is another text'])]
    #[TestWith([PHP_EOL . 'This is a text' . PHP_EOL . 'This is another text'])]
    #[TestWith(['This is a text' . PHP_EOL . 'This is another text' . PHP_EOL])]
    #[TestWith([PHP_EOL . 'This is a text' . PHP_EOL . 'This is another text' . PHP_EOL])]
    #[TestWith([PHP_EOL . 'This is a text' . PHP_EOL . 'This is another text' . PHP_EOL . PHP_EOL])]
    public function testAutoParagraph(string $text): void
    {
        $expected = '<p class="mb-2">This is a text</p>' . PHP_EOL .
            '<p class="mb-0">This is another text</p>';
        $result = $this->Text->autoParagraph($text);
        $this->assertSame($expected, $result);
    }

    /**
     * @link \Cake\Essentials\View\Helper\TextHelper::autoParagraph()
     */
    #[Test]
    #[TestWith(['This is a text'])]
    #[TestWith(['This is a text' . PHP_EOL])]
    #[TestWith([PHP_EOL . 'This is a text'])]
    #[TestWith([PHP_EOL . 'This is a text' . PHP_EOL])]
    public function testAutoParagraphOnSingleLine(string $text): void
    {
        $expected = '<p class="mb-0">This is a text</p>';
        $result = $this->Text->autoParagraph($text);
        $this->assertSame($expected, $result);
    }

    /**
     * @link \Cake\Essentials\View\Helper\TextHelper::autoParagraph()
     */
    #[Test]
    #[TestWith([null])]
    #[TestWith([''])]
    public function testAutoParagraphOnEmptyValues(?string $text): void
    {
        $this->assertEmpty($this->Text->autoParagraph($text));
    }

    public static function provideHighlight(): Generator
    {
        yield [
            'This is my text, with <span class="text-bg-warning">some</span> characters',
            'some',
        ];

        yield [
            'This is my text, with <div class="my-custom-class">some</div> characters',
            'some',
            ['format' => '<div class="my-custom-class">\1</div>'],
        ];

        yield [
            '<span class="text-bg-warning">This</span> is my text, with some <span class="text-bg-warning">characters</span>',
            ['characters', 'this'],
        ];

        yield [
            '<div class="my-custom-class">This</div> is my text, with some <div class="my-custom-class">characters</div>',
            ['characters', 'this'],
            ['format' => '<div class="my-custom-class">\1</div>'],
        ];
    }

    /**
     * @link \Cake\Essentials\View\Helper\TextHelper::highlight()
     */
    #[Test]
    #[DataProvider('provideHighlight')]
    public function testHighlight(string $expectedHighlight, string|array $phrase, array $options = []): void
    {
        $result = $this->Text->highlight(text: 'This is my text, with some characters', phrase: $phrase, options: $options);
        $this->assertSame($expectedHighlight, $result);
    }
}
