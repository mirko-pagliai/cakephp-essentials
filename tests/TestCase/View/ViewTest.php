<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase;

use Cake\Essentials\View\View;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * ViewTest.
 */
#[CoversClass(View::class)]
class ViewTest extends TestCase
{
    protected View $View;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->View = new View();
    }

    /**
     * @link \Cake\Essentials\View\View::initialize()
     *
     * @param class-string $expectedClass
     * @param string $helper
     * @return void
     */
    #[Test]
    #[TestWith(['Cake\Essentials\View\Helper\AlertHelper', 'Alert'])]
    #[TestWith(['Cake\Essentials\View\Helper\FlashHelper', 'Flash'])]
    #[TestWith(['Cake\Essentials\View\Helper\FormHelper', 'Form'])]
    #[TestWith(['Cake\Essentials\View\Helper\HtmlHelper', 'Html'])]
    #[TestWith(['BootstrapUI\View\Helper\PaginatorHelper', 'Paginator'])]
    public function testInitializeLoadedHelpers(string $expectedClass, string $helper): void
    {
        $HelperRegistry = $this->View->helpers();
        $this->assertInstanceOf($expectedClass, $HelperRegistry->get($helper));
    }

    /**
     * @link \Cake\Essentials\View\View::assignTitle()
     */
    #[Test]
    public function testAssignTitle(): void
    {
        $title = 'My title';
        $result = $this->View->assignTitle($title);
        $this->assertEquals($this->View, $result);
        $this->assertSame($title, $this->View->fetch('main_title'));
    }

    /**
     * @link \Cake\Essentials\View\View::startMainLinks()
     */
    #[Test]
    public function testStartMainLinks(): void
    {
        $expected = '<a href="/my-link" title="My link">My link</a>';

        $result = $this->View->startMainLinks();
        echo $this->View->Html->link('My link', '/my-link');
        $this->View->end();

        $this->assertEquals($this->View, $result);
        $this->assertSame($expected, $this->View->fetch('main_links'));
    }
}
