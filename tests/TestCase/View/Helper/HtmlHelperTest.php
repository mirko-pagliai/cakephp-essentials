<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use BadMethodCallException;
use Cake\Essentials\View\Helper\HtmlHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
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
