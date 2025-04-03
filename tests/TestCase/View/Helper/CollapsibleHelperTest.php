<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use BadMethodCallException;
use Cake\Essentials\View\Helper\CollapsibleHelper;
use Cake\Essentials\View\View;
use Cake\TestSuite\TestCase;
use Generator;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * CollapsibleHelperTest.
 */
#[CoversClass(CollapsibleHelper::class)]
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
        $View = new View();

        $this->Collapsible = new CollapsibleHelper($View);
    }

    public static function providerTestLink(): Generator
    {
        $defaultExpected = [
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

        /**
         * With default arguments.
         */

        yield [$defaultExpected];

        /**
         * With `$alreadyOpen` as `true`
         */
        $expectedWithAlreadyOpen = $defaultExpected;
        $expectedWithAlreadyOpen['a']['aria-expanded'] = 'true';
        $expectedWithAlreadyOpen['i']['class'] = 'ms-1 bi bi-chevron-up';

        yield [$expectedWithAlreadyOpen, [], true];

        /**
         * With a custom class
         */
        $expectedWithCustomClass = $defaultExpected;
        $expectedWithCustomClass['a']['class'] = 'custom-class text-decoration-none';

        yield [$expectedWithCustomClass, ['class' => 'custom-class']];
    }

    #[Test]
    #[DataProvider('providerTestLink')]
    public function testLink(array $expectedHtml, array $options = [], bool $alreadyOpen = false): void
    {
        $result = $this->Collapsible->link(title: 'Title', collapsibleId: 'my-id', options: $options, alreadyOpen: $alreadyOpen);
        $this->assertHtml($expectedHtml, $result);
    }

    #[Test]
    public function testLinkWithCustomToggleIcons(): void
    {
        $this->Collapsible->setConfig(key: 'toggleIcon.open', value: '1-circle');
        $this->Collapsible->setConfig(key: 'toggleIcon.close', value: '2-circle');

        $result = $this->Collapsible->link(title: 'Title', collapsibleId: 'my-id');
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

        $result = $this->Collapsible->link(title: 'Title', collapsibleId: 'my-id');

        // Title does not contain icons. `onclick` attribute is not present
        $this->assertStringContainsString('>Title</a>', $result);
        $this->assertStringNotContainsString('onclick=', $result);
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
