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
     * @var \Cake\Essentials\View\Helper\HtmlHelper&\PHPUnit\Framework\MockObject\MockObject
     */
    protected HtmlHelper $HtmlHelper;

    /**
     * @inheritDoc
     */
    #[Override]
    protected function setUp(): void
    {
        $View = new View();

        $this->HtmlHelper = $this->getMockBuilder(HtmlHelper::class)
            ->setConstructorArgs([$View])
            ->onlyMethods(['link'])
            ->getMock();

        $this->Collapsible = new CollapsibleHelper($View);
        $this->Collapsible->getView()->set('Html', $this->HtmlHelper);
    }

    public function testLink(): void
    {
        $this->HtmlHelper
            ->expects($this->once())
            ->method('link')
            ->with('Title');

        $this->Collapsible->link(title: 'Title', collapsibleId: 'my-id', options: [], alreadyOpen: false);
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
