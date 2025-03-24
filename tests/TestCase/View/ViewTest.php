<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase;

use Cake\Essentials\View\Helper\FormHelper;
use Cake\Essentials\View\Helper\HtmlHelper;
use Cake\Essentials\View\View;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

/**
 * ViewTest.
 */
#[CoversClass(View::class)]
class ViewTest extends TestCase
{
    /**
     * @var \Cake\Essentials\View\View
     */
    protected View $View;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->View = new View();
        $this->View->initialize();
    }

    #[Test]
    public function testInitialize(): void
    {
        // `HtmlHelper`
        $expectedIconDefaultsConfig = [
            'tag' => 'i',
            'namespace' => 'fas',
            'prefix' => 'fa',
            'size' => null,
        ];
        $HtmlHelper = $this->View->helpers()->get('Html');
        $this->assertInstanceOf(HtmlHelper::class, $HtmlHelper);
        $this->assertSame($expectedIconDefaultsConfig, $HtmlHelper->getConfig('iconDefaults'));

        // `FormHelper`
        $FormHelper = $this->View->helpers()->get('Form');
        $this->assertInstanceOf(FormHelper::class, $FormHelper);
    }

    #[Test]
    public function testAssignTitle(): void
    {
        $title = 'My title';
        $result = $this->View->assignTitle($title);
        $this->assertEquals($this->View, $result);
        $this->assertSame($title, $this->View->fetch('title'));
    }

    #[Test]
    public function testStartMainLinks(): void
    {
        $expected = '<a href="/my-link">My link</a>';

        $result = $this->View->startMainLinks();
        echo $this->View->Html->link('My link', '/my-link');
        $this->View->end();

        $this->assertEquals($this->View, $result);
        $this->assertSame($expected, $this->View->fetch('main_links'));
    }
}
