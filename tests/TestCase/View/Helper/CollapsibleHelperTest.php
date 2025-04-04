<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use BadMethodCallException;
use Cake\Essentials\View\Helper\CollapsibleHelper;
use Cake\Essentials\View\Helper\HtmlHelper;
use Cake\Essentials\View\View;
use Cake\TestSuite\TestCase;
use Generator;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
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

    public static function providerTestLink(): Generator
    {
        // Default
        $expected = [
            'a' => [
                'href' => '#my-id',
                'aria-controls' => 'my-id',
                'aria-expanded' => 'false',
                'data-bs-toggle' => 'collapse',
                'role' => 'button',
                'class' => 'collapsed text-decoration-none',
                'title' => 'Title',
            ],
            'Title',
            'span' => ['class' => 'd-print-none toggle-icon', 'data-open-icon', 'data-close-icon'],
            'i' => ['class' => 'ms-1 bi bi-chevron-down'],
            '/i',
            '/span',
            '/a',
        ];

        // With defaults
        yield [$expected];

        // With `$alreadyOpen` as `true`
        $expected['a']['aria-expanded'] = 'true';
        $expected['a']['class'] = 'text-decoration-none';
        $expected['i']['class'] = 'ms-1 bi bi-chevron-up';
        yield [$expected, true];
    }

    #[Test]
    #[DataProvider('providerTestLink')]
    public function testLink(array $expectedHtml, bool $alreadyOpen = false): void
    {
        $result = $this->Collapsible->link(title: 'Title', collapsibleId: 'my-id', alreadyOpen: $alreadyOpen);
        $this->assertHtml($expectedHtml, $result);
    }

    #[Test]
    #[TestWith(['class="custom-class collapsed text-decoration-none"'])]
    #[TestWith(['class="custom-class text-decoration-none"', true])]
    public function testLinkCustomClass(string $expected, bool $alreadyOpen = false): void
    {
        $result = $this->Collapsible->link(title: 'Title', options: ['class' => 'custom-class'], alreadyOpen: $alreadyOpen);
        $this->assertStringContainsString($expected, $result);
    }

    #[Test]
    public function testLinkWithIcon(): void
    {
        $result = $this->Collapsible->link(title: 'Title', options: ['icon' => 'house']);
        $this->assertStringContainsString('<i class="bi bi-house"></i> Title', $result);
    }

    #[Test]
    public function testLinkWithCustomToggleIcons(): void
    {
        $this->Collapsible->setConfig(key: 'toggleIcon', value: ['open' => '1-circle', 'close' => '2-circle']);

        $result = $this->Collapsible->link(title: 'Title');
        $this->assertStringContainsString('toggle-icon', $result);
        $this->assertStringContainsString('bi bi-1-circle', $result);
        $this->assertStringContainsString('bi bi-2-circle', $result);
    }

    #[Test]
    #[TestWith([''])]
    #[TestWith([null])]
    #[TestWith([false])]
    public function testLinkWithDisabledToggleIcons(mixed $iconValue): void
    {
        $this->Collapsible->setConfig(key: 'toggleIcon', value: $iconValue);

        /**
         * Title does not contain icons.
         * `span` with `toggle-icon` class is not present.
         * There is no `text-decoration-none` class.
         */
        $result = $this->Collapsible->link(title: 'Title');
        $this->assertStringContainsString('>Title</a>', $result);
        $this->assertStringNotContainsString('toggle-icon', $result);
        $this->assertStringNotContainsString('text-decoration-none', $result);

        /**
         * Title does not contain toggle icons, only normal icon.
         * `span` with `toggle-icon` class is not present.
         * There is the `text-decoration-none` class (because of the normal icon).
         */
        $result = $this->Collapsible->link(title: 'Title', options: ['icon' => 'house']);
        $this->assertStringContainsString('><i class="bi bi-house"></i> Title</a>', $result);
        $this->assertStringNotContainsString('toggle-icon', $result);
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

    #[Test]
    public function testContentConsecutiveMethodCalls(): void
    {
        $this->Collapsible->link(title: 'Title');
        $result = $this->Collapsible->content('Text');
        $this->assertNotEmpty($result);

        $this->expectException(BadMethodCallException::class);
        $this->Collapsible->content('Text');
    }
}
