<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

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
    #[TestWith(['<i class="fas fa-home"></i>', ['icon' => 'home']])]
    #[TestWith(['<i class="fas fa-home"></i>', ['icon' => ['name' => 'home']]])]
    #[TestWith(['<i class="fas fa-home fa-lg"></i>', ['icon' => ['name' => 'home', 'size' => 'lg']]])]
    public function testAddIconToTitle(string $expectedIcon, array $options): void
    {
        [$resultTitle, $resultOptions] = $this->Html->addIconToTitle(title: 'Title', options: $options);
        $this->assertSame($expectedIcon . ' Title', $resultTitle);
        $this->assertSame(['class' => 'text-decoration-none'], $resultOptions);
    }

    /**
     * `icon` is empty.
     *
     * Expects the title to be unchanged and the options array to be empty.
     */
    #[Test]
    #[TestWith([[]])]
    #[TestWith([['icon' => null]])]
    #[TestWith([['icon' => false]])]
    #[TestWith([['icon' => []]])]
    public function testAddIconToTitleEmptyIcon(array $options): void
    {
        [$resultTitle, $resultOptions] = $this->Html->addIconToTitle(title: 'Title', options: $options);
        $this->assertSame('Title', $resultTitle);
        $this->assertSame([], $resultOptions);
    }

    #[Test]
    public function testAddIconToTitleMissingName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing icon `name` value');
        $this->Html->addIconToTitle(title: 'Title', options: ['icon' => ['size' => 'lg']]);
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
        $result = $this->Html->addTooltip($options);
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
    public function testLink(): void
    {
        $expected = '<a href="#url" class="text-decoration-none"><i class="fas fa-home"></i> Title</a>';
        $result = $this->Html->link(title: 'Title', url: '#url', options: ['icon' => 'home']);
        $this->assertSame($expected, $result);
    }

    #[Test]
    public function testLinkWithTitleAsArray(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('`$title` as array is not supported');
        $this->Html->link(['controller' => 'Pages', 'action' => 'index']);
    }
}
