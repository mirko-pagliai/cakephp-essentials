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
    #[TestWith(['Title', ''])]
    #[TestWith(['<i class="fas fa-home"></i> Title', 'home'])]
    #[TestWith(['<i class="fas fa-home"></i> Title', ['name' => 'home']])]
    #[TestWith(['<i class="fas fa-home fa-lg"></i> Title', ['name' => 'home', 'size' => 'lg']])]
    public function testAddIconToTitle(string $expected, string|array $icon): void
    {
        $result = $this->Html->addIconToTitle(title: 'Title', options: compact('icon'));
        $this->assertSame($expected, $result);
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
        $expected = '<a href="#url"><i class="fas fa-home"></i> Title</a>';
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
