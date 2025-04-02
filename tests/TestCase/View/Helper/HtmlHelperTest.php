<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use ArrayObject;
use BadMethodCallException;
use Cake\Essentials\View\Helper\HtmlHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * HtmlHelperTest.
 */
#[CoversClass(HtmlHelper::class)]
class HtmlHelperTest extends TestCase
{
    /**
     * @var \Cake\Essentials\View\Helper\HtmlHelper
     */
    protected HtmlHelper $Html;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->Html = new HtmlHelper(new View());
    }

    #[Test]
    #[TestWith(['h1'])]
    #[TestWith(['span'])]
    public function testCallMagicMethodPositionalAndNamedArguments(string $tag): void
    {
        $expected = sprintf('<%s class="custom-class">My text</%s>', $tag, $tag);
        $options = ['class' => 'custom-class'];

        //Both positionals
        $result = $this->Html->{$tag}('My text', $options);
        $this->assertSame($expected, $result);

        //Both named
        $result = $this->Html->{$tag}(text: 'My text', options: $options);
        $this->assertSame($expected, $result);

        //Only `optional` is named
        $result = $this->Html->{$tag}('My text', options: $options);
        $this->assertSame($expected, $result);

        $expected = sprintf('<%s><i style="color: red" class="bi bi-home"></i> My text</%s>', $tag, $tag);
        $result = $this->Html->{$tag}('My text', options: ['icon' => ['name' => 'home', 'style' => 'color: red']]);
        $this->assertSame($expected, $result);
    }

    #[Test]
    #[TestWith(['code', '<code>My text</code>'])]
    #[TestWith(['em', '<em>My text</em>'])]
    #[TestWith(['kbd', '<kbd>My text</kbd>'])]
    #[TestWith(['h1', '<h1>My text</h1>'])]
    #[TestWith(['h2', '<h2>My text</h2>'])]
    #[TestWith(['h3', '<h3>My text</h3>'])]
    #[TestWith(['h4', '<h4>My text</h4>'])]
    #[TestWith(['h5', '<h5>My text</h5>'])]
    #[TestWith(['h6', '<h6>My text</h6>'])]
    #[TestWith(['pre', '<pre>My text</pre>'])]
    #[TestWith(['small', '<small>My text</small>'])]
    #[TestWith(['span', '<span>My text</span>'])]
    #[TestWith(['strong', '<strong>My text</strong>'])]
    #[TestWith(['title', '<title>My text</title>'])]
    #[TestWith(['underline', '<u>My text</u>'])]
    #[TestWith(['var', '<var>My text</var>'])]
    public function testCallMagicMethodWithAllDefinedTags(string $tag, string $expected): void
    {
        $result = $this->Html->{$tag}('My text');
        $this->assertSame($expected, $result);
    }

    #[Test]
    public function testCallMagicMethodTooFewArguments(): void
    {
        $this->expectExceptionMessage('Too few arguments for `' . $this->Html::class . '::h1()`, 0 passed and at least 1 expected');
        $this->Html->h1();
    }

    #[Test]
    public function testCallMagicMethodTooManyArguments(): void
    {
        $this->expectExceptionMessage('Too many arguments for `' . $this->Html::class . '::h1()`, 3 passed and at most 2 expected');
        // @phpstan-ignore-next-line
        $this->Html->h1('My text', [], 'noExistingArgument');
    }

    #[Test]
    #[TestWith(['', []])]
    #[TestWith(['<i class="bi bi-house"></i>', ['icon' => 'house']])]
    #[TestWith(['<i class="bi bi-house"></i>', ['icon' => ['name' => 'house']]])]
    #[TestWith(['<i class="fs-5 bi bi-house"></i>', ['icon' => ['name' => 'house', 'class' => 'fs-5']]])]
    #[TestWith(['', ['icon' => null]])]
    #[TestWith(['', ['icon' => false]])]
    #[TestWith(['', ['icon' => []]])]
    public function testBuildIcon(string $expectedIcon, array $options): void
    {
        $result = $this->Html->buildIcon($options);
        $this->assertSame($expectedIcon, $result);
    }

    #[Test]
    public function testBuildIconWithMissingName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing icon `name` value');
        $this->Html->buildIcon(['icon' => ['size' => 'lg']]);
    }

    #[Test]
    #[TestWith(['Title', 'Title'])]
    #[TestWith(['Title', 'Title', ['icon' => null]])]
    #[TestWith(['Title', 'Title', ['icon' => false]])]
    #[TestWith(['Title', 'Title', ['icon' => []]])]
    #[TestWith(['<i class="bi bi-home"></i> Title', 'Title', ['icon' => 'home']])]
    #[TestWith(['<i class="bi bi-home"></i> Title', 'Title', ['icon' => ['name' => 'home']]])]
    #[TestWith(['<i class="fs-5 bi bi-home"></i> Title', 'Title', ['icon' => ['name' => 'home', 'class' => 'fs-5']]])]
    #[TestWith(['<i class="bi bi-home"></i>', '', ['icon' => 'home']])]
    #[TestWith(['<i class="fs-5 bi bi-home"></i>', '', ['icon' => ['name' => 'home', 'class' => 'fs-5']]])]
    public function testAddIconToTitle(string $expectedTitle, string $title, array $options = []): void
    {
        [$resultTitle, $resultOptions] = $this->Html->addIconToTitle(title: $title, options: $options);

        $this->assertSame($expectedTitle, $resultTitle);

        //The `icon` option has been consumed
        unset($options['icon']);
        $this->assertSame($options, $resultOptions);
    }

    #[Test]
    #[TestWith([['popover' => 'First<br />Second']])]
    #[TestWith([['popover' => ['First', 'Second']]])]
    #[TestWith([['data-bs-content' => 'something-else', 'popover' => 'First<br />Second', 'data-bs-toggle' => 'something-else']])]
    public function testAddPopover(array $options): void
    {
        $expected = [
            'data-bs-content' => 'First<br />Second',
            'data-bs-html' => 'true',
            'data-bs-trigger' => 'focus',
            'data-bs-toggle' => 'popover',
            'role' => 'button',
            'tabindex' => 0,
        ];
        $result = $this->Html->addPopover(options: $options);
        $this->assertSame($expected, $result);
    }

    #[Test]
    public function testAddPopoverWithNoPopoverOption(): void
    {
        $options = ['class' => 'custom-class'];
        $result = $this->Html->addPopover($options);
        $this->assertSame($options, $result);
    }

    #[Test]
    #[TestWith([['tooltip' => 'First<br />Second']])]
    #[TestWith([['tooltip' => ['First', 'Second']]])]
    #[TestWith([['data-bs-html' => 'false', 'tooltip' => 'First<br />Second', 'data-bs-toggle' => 'something-else']])]
    public function testAddTooltip(array $options): void
    {
        $expected = [
            'data-bs-html' => 'true',
            'data-bs-title' => 'First<br />Second',
            'data-bs-toggle' => 'tooltip',
        ];
        $result = $this->Html->addTooltip(options: $options);
        $this->assertSame($expected, $result);
    }

    #[Test]
    public function testAddTooltipWithNoTooltipOption(): void
    {
        $options = ['class' => 'custom-class'];
        $result = $this->Html->addTooltip($options);
        $this->assertSame($options, $result);
    }

    #[Test]
    #[TestWith(['<abbr class="initialism">My text</abbr>', 'My text', ''])]
    #[TestWith(['<abbr class="initialism" title="A title">My text</abbr>', 'My text', 'A title'])]
    #[TestWith(['<abbr class="my-custom-class">My text</abbr>', 'My text', '', ['class' => 'my-custom-class']])]
    #[TestWith(['<abbr title="Good title" class="initialism">My text</abbr>', 'My text', 'Good title', ['title' => 'Bad title']])]
    #[TestWith(['<abbr title="Good title" class="initialism">My text</abbr>', 'My text', '', ['title' => 'Good title']])]
    public function testAbbr(string $expectedAbbr, string $text, string $title, array $options = []): void
    {
        $result = $this->Html->abbr(text: $text, title: $title, options: $options);
        $this->assertSame($expectedAbbr, $result);
    }

    #[Test]
    #[TestWith(['<button type="button" class="btn">My text</button>'])]
    #[TestWith(['<button type="button" class="btn"></button>', ''])]
    #[TestWith(['<button class="btn" type="button">My text</button>', 'My text', ['class' => 'btn']])]
    #[TestWith(['<button class="btn btn-primary" type="button">My text</button>', 'My text', ['class' => 'btn btn-primary']])]
    #[TestWith(['<button class="btn-primary btn" type="button">My text</button>', 'My text', ['class' => 'btn-primary']])]
    #[TestWith(['<button type="reset" class="btn">My text</button>', 'My text', ['type' => 'reset']])]
    public function testButton(string $expectedButton, string $text = 'My text', array $options = []): void
    {
        $result = $this->Html->button(text: $text, options: $options);
        $this->assertSame($expectedButton, $result);
    }

    #[Test]
    public function testFlushDiv(): void
    {
        $list = [
            'First string',
            'Second string',
        ];

        $expected = '<div class="list-group list-group-flush">' .
            '<div class="list-group-item">First string</div>' .
            '<div class="list-group-item">Second string</div>' .
            '</div>';
        $result = $this->Html->flushDiv(list: $list);
        $this->assertSame($expected, $result);

        //Same result with a Traversable
        $result = $this->Html->flushDiv(list: new ArrayObject($list));
        $this->assertSame($expected, $result);

        //With custom classes (for wrapper and items)
        $expected = '<div class="custom-wrapper-class list-group list-group-flush">' .
            '<div class="custom-item-class list-group-item">First string</div>' .
            '<div class="custom-item-class list-group-item">Second string</div>' .
            '</div>';
        $result = $this->Html->flushDiv(
            list: $list,
            options: ['class' => 'custom-wrapper-class'],
            itemOptions: ['class' => 'custom-item-class']
        );
        $this->assertSame($expected, $result);
    }

    public static function imageProvider(): Generator
    {
        yield [
            '<img src="/path/to/image.png?k=v" class="img-fluid" alt="image.png">',
            '/path/to/image.png?k=v',
        ];

        yield [
            '<img src="/path/to/image.png?k=v" alt="my-custom-alt" class="my-custom-class">',
            '/path/to/image.png?k=v',
            ['alt' => 'my-custom-alt', 'class' => 'my-custom-class'],
        ];

        yield [
            '<img src="/path/to/image.png?k=v" data-bs-html="true" data-bs-title="My tooltip" data-bs-toggle="tooltip" class="img-fluid">',
            '/path/to/image.png?k=v',
            ['tooltip' => 'My tooltip', 'alt' => false],
        ];
    }

    #[Test]
    #[DataProvider('imageProvider')]
    public function testImage(string $expectedImage, string $path, array $options = []): void
    {
        $result = $this->Html->image($path, $options);
        $this->assertSame($expectedImage, $result);
    }

    #[Test]
    #[TestWith(['<a href="#url" title="Title">Title</a>', 'Title'])]
    #[TestWith(['<a href="#url" title="Title"><span>Title</span></a>', '<span>Title</span>'])]
    #[TestWith(['<a href="#url" title="Title" class="text-decoration-none"><i class="bi bi-home"></i> Title</a>', 'Title', ['icon' => 'home']])]
    #[TestWith(['<a href="#url" title="Attribute title">Title</a>', 'Title', ['title' => 'Attribute title']])]
    public function testLink(string $expectedLink, string $title, array $options = []): void
    {
        $result = $this->Html->link(title: $title, url: '#url', options: $options);
        $this->assertSame($expectedLink, $result);
    }

    #[Test]
    public function testLinkWithPopover(): void
    {
        $expected = 'data-bs-content="My popover" data-bs-html="true" data-bs-trigger="focus" data-bs-toggle="popover" role="button" tabindex="0"';
        $result = $this->Html->link(title: 'Title', url: '#url', options: ['popover' => 'My popover']);
        $this->assertStringContainsString($expected, $result);
    }

    #[Test]
    public function testLinkWithTooltip(): void
    {
        $expected = 'data-bs-html="true" data-bs-title="My tooltip" data-bs-toggle="tooltip"';
        $result = $this->Html->link(title: 'Title', url: '#url', options: ['tooltip' => 'My tooltip']);
        $this->assertStringContainsString($expected, $result);
    }

    #[Test]
    public function testLinkWithTitleAsArray(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('`$title` as array is not supported');
        $this->Html->link(['controller' => 'Pages', 'action' => 'index']);
    }

    #[Test]
    #[TestWith(['<span>My text</span>'])]
    #[TestWith(['<span><i class="bi bi-home"></i> My text</span>', ['icon' => 'home']])]
    #[TestWith(['<span data-bs-html="true" data-bs-title="My tooltip" data-bs-toggle="tooltip">My text</span>', ['tooltip' => 'My tooltip']])]
    public function testTag(string $expectedTag, array $options = []): void
    {
        $result = $this->Html->tag(name: 'span', text: 'My text', options: $options);
        $this->assertSame($expectedTag, $result);
    }
}
