<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use BadMethodCallException;
use Cake\Essentials\View\Helper\CollapsibleHelper;
use Cake\Essentials\View\Helper\HtmlHelper;
use Cake\Essentials\View\View;
use Cake\TestSuite\TestCase;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * CollapsibleHelperTest.
 */
#[CoversClass(CollapsibleHelper::class)]
#[UsesClass(HtmlHelper::class)]
class CollapsibleHelperTest extends TestCase
{
    /**
     * @var \Cake\Essentials\View\Helper\CollapsibleHelper
     */
    protected CollapsibleHelper $Collapsible;

    /**
     * @inheritDoc
     */
    #[Override]
    protected function setUp(): void
    {
        $this->Collapsible = new CollapsibleHelper(new View());
    }

    #[Test]
    public function testLink(): void
    {
        $expected = [
            'a' => [
                'href' => '#my-id',
                'aria-controls' => 'my-id',
                'aria-expanded' => 'false',
                'data-bs-toggle' => 'collapse',
                'onclick' => 'preg:/(?:[^"]+)/',
                'role' => 'button',
                'class' => 'text-decoration-none',
                'title' => 'Title',
            ],
            'Title',
            'i' => ['class' => 'ms-1 bi bi-chevron-down'],
            '/i',
            '/a',
        ];
        $result = $this->Collapsible->link(title: 'Title', collapsibleId: 'my-id');
        $this->assertHtml($expected, $result);
    }

    #[Test]
    public function testLinkWithAlreadyOpen(): void
    {
        $result = $this->Collapsible->link(title: 'Title', alreadyOpen: true);
        $this->assertStringContainsString('aria-expanded="true"', $result);
        $this->assertStringContainsString('>Title<i class="ms-1 bi bi-chevron-up"></i></a>', $result);
    }

    #[Test]
    public function testLinkCustomClass(): void
    {
        $result = $this->Collapsible->link(title: 'Title', options: ['class' => 'custom-class']);
        $this->assertStringContainsString('class="custom-class text-decoration-none"', $result);
    }

    #[Test]
    public function testLinkWithIcon(): void
    {
        $result = $this->Collapsible->link(title: 'Title', options: ['icon' => 'house']);
        $this->assertStringContainsString('><i class="bi bi-house"></i> Title<i class="ms-1 bi bi-chevron-down"></i></a>', $result);
    }

    #[Test]
    public function testLinkWithCustomToggleIcons(): void
    {
        $this->Collapsible->setConfig(key: 'toggleIcon.open', value: '1-circle');
        $this->Collapsible->setConfig(key: 'toggleIcon.close', value: '2-circle');

        $result = $this->Collapsible->link(title: 'Title');
        $this->assertStringContainsString('bi bi-1-circle', $result);
        $this->assertStringContainsString('bi bi-2-circle', $result);
        $this->assertStringContainsString('onclick=', $result);
    }

    #[Test]
    #[TestWith([''])]
    #[TestWith([null])]
    #[TestWith([false])]
    public function testLinkWithDisabledToggleIcons(mixed $iconValue): void
    {
        $this->Collapsible->setConfig(key: 'toggleIcon', value: $iconValue);

        /**
         * Title does not contain toggle icons.
         * `onclick` attribute is not present.
         * There is no `text-decoration-none` class.
         */
        $result = $this->Collapsible->link(title: 'Title');
        $this->assertStringContainsString('>Title</a>', $result);
        $this->assertStringNotContainsString('onclick=', $result);
        $this->assertStringNotContainsString('text-decoration-none', $result);

        /**
         * Title does not contain toggle icons, only normal icon.
         * `onclick` attribute is not present.
         * There is the `text-decoration-none` class (because of the normal icon).
         */
        $result = $this->Collapsible->link(title: 'Title', options: ['icon' => 'house']);
        $this->assertStringContainsString('><i class="bi bi-house"></i> Title</a>', $result);
        $this->assertStringNotContainsString('onclick=', $result);
        $this->assertStringContainsString('class="text-decoration-none"', $result);
    }

    #[Test]
    #[TestWith(['<div id="my-id" class="collapse">Text</div>', 'Text'])]
    #[TestWith(['<div id="my-id" class="collapse">First<br />Second</div>', ['First', 'Second']])]
    #[TestWith(['<div id="my-id" class="custom-class collapse">Text</div>', 'Text', ['class' => 'custom-class']])]
    public function testContent(string $expectedContent, string|array $content, array $options = []): void
    {
        $this->Collapsible->link(title: 'Title', collapsibleId: 'my-id');

        $result = $this->Collapsible->content($content, $options);
        $this->assertSame($expectedContent, $result);
    }

    #[Test]
    #[TestWith(['<div id="my-id" class="collapse show">Text</div>', 'Text'])]
    #[TestWith(['<div id="my-id" class="collapse show">First<br />Second</div>', ['First', 'Second']])]
    #[TestWith(['<div id="my-id" class="custom-class collapse show">Text</div>', 'Text', ['class' => 'custom-class']])]
    public function testContentAlreadyOpened(string $expectedContent, string|array $content, array $options = []): void
    {
        $this->Collapsible->link(title: 'Title', collapsibleId: 'my-id', alreadyOpen: true);

        $result = $this->Collapsible->content($content, $options);
        $this->assertSame($expectedContent, $result);
    }

    #[Test]
    public function testContentWithoutLinkBeingCalled(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Seems that the link to open the collapsible was not set, perhaps the `link()` method was not called?');
        $this->Collapsible->content('Text');
    }
}
