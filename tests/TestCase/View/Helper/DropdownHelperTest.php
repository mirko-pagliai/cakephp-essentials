<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use BadMethodCallException;
use Cake\Essentials\View\Helper\DropdownHelper;
use Cake\Essentials\View\Helper\FormHelper;
use Cake\Essentials\View\Helper\HtmlHelper;
use Cake\Essentials\View\View;
use Cake\TestSuite\TestCase;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * DropdownHelperTest
 */
#[CoversClass(DropdownHelper::class)]
#[UsesClass(FormHelper::class)]
#[UsesClass(HtmlHelper::class)]
class DropdownHelperTest extends TestCase
{
    protected DropdownHelper $Dropdown;

    /**
     * @var \Mockery\MockInterface&\Cake\Essentials\View\Helper\FormHelper
     */
    protected FormHelper $Form;

    /**
     * @var \Mockery\MockInterface&\Cake\Essentials\View\Helper\HtmlHelper
     */
    protected HtmlHelper $Html;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $View = new View();

        /** @var \Mockery\MockInterface&\Cake\Essentials\View\Helper\FormHelper $FormHelper */
        $FormHelper = Mockery::mock(FormHelper::class . '[postLink]', [$View]);
        $this->Form = $FormHelper;

        /** @var \Mockery\MockInterface&\Cake\Essentials\View\Helper\HtmlHelper $HtmlHelper */
        $HtmlHelper = Mockery::mock(HtmlHelper::class . '[link]', [$View]);
        $this->Html = $HtmlHelper;

        $View->helpers()->set('Html', $this->Html);
        $View->helpers()->set('Form', $this->Form);

        $this->Dropdown = new DropdownHelper($View);
    }

    /**
     * @link \Cake\Essentials\View\Helper\DropdownHelper::create()
     */
    #[Test]
    public function testCreate(): void
    {
        $this->Html
            ->shouldReceive('link')
            ->with(
                'My dropdown main link',
                '#',
                [
                    'class' => 'my-custom-class dropdown-toggle',
                    'aria-expanded' => 'false',
                    'data-bs-toggle' => 'dropdown',
                    'role' => 'button',
                ],
            )
            ->once();

        $result = $this->Dropdown->create(title: 'My dropdown main link', options: ['class' => 'my-custom-class']);
        $this->assertSame($result, $this->Dropdown);
    }

    /**
     * @link \Cake\Essentials\View\Helper\DropdownHelper::link()
     */
    #[Test]
    public function testLink(): void
    {
        $this->Html
            ->shouldReceive('link')
            ->with(
                'My link',
                '#',
                ['class' => 'my-custom-class dropdown-item'],
            )
            ->once();

        $result = $this->Dropdown->link(
            title: 'My link',
            url: '#',
            options: ['class' => 'my-custom-class'],
        );
        $this->assertSame($result, $this->Dropdown);
    }

    /**
     * @link \Cake\Essentials\View\Helper\DropdownHelper::linkFromPath()
     */
    #[Test]
    public function testLinkFromPath(): void
    {
        $this->Html
            ->shouldReceive('link')
            ->with(
                'My link from path',
                ['_path' => 'Users::index', '?' => ['k' => 'v']],
                ['class' => 'my-custom-class dropdown-item'],
            )
            ->once();

        $result = $this->Dropdown->linkFromPath(
            title: 'My link from path',
            path: 'Users::index',
            params: ['?' => ['k' => 'v']],
            options: ['class' => 'my-custom-class'],
        );
        $this->assertSame($result, $this->Dropdown);
    }

    /**
     * @link \Cake\Essentials\View\Helper\DropdownHelper::deleteLink()
     */
    #[Test]
    public function testDeleteLink(): void
    {
        $this->Form
            ->shouldReceive('postLink')
            ->with(
                'My post link',
                '#',
                [
                    'class' => 'my-custom-class dropdown-item',
                    'method' => 'delete',
                    'confirm' => 'Are you sure you want to delete this item?',
                ],
            )
            ->once();

        $result = $this->Dropdown->deleteLink(
            title: 'My post link',
            url: '#',
            options: ['class' => 'my-custom-class'],
        );
        $this->assertSame($result, $this->Dropdown);
    }

    /**
     * @link \Cake\Essentials\View\Helper\DropdownHelper::postLink()
     */
    #[Test]
    public function testPostLink(): void
    {
        $this->Form
            ->shouldReceive('postLink')
            ->with(
                'My post link',
                '#',
                ['class' => 'my-custom-class dropdown-item'],
            )
            ->once();
        $result = $this->Dropdown->postLink(
            title: 'My post link',
            url: '#',
            options: ['class' => 'my-custom-class'],
        );
        $this->assertSame($result, $this->Dropdown);
    }

    /**
     * @link \Cake\Essentials\View\Helper\DropdownHelper::hasLinks()
     */
    #[Test]
    public function testHasLinks(): void
    {
        $Dropdown = new DropdownHelper(new View());

        $this->assertFalse($Dropdown->hasLinks());

        $Dropdown->link('My link', '#');

        $this->assertTrue($Dropdown->hasLinks());
    }

    /**
     * @link \Cake\Essentials\View\Helper\DropdownHelper::render()
     */
    #[Test]
    public function testRender(): void
    {
        $expected = '<div class="dropdown">' .
            '<a href="#" aria-expanded="false" data-bs-toggle="dropdown" role="button" class="dropdown-toggle" title="My dropdown">' .
            'My dropdown' .
            '</a>' .
            '<ul class="dropdown-menu">' .
            '<li><a href="#first" class="dropdown-item" title="First link">First link</a></li>' .
            '<li><a href="#second" class="dropdown-item" title="Second link">Second link</a></li>' .
            '</ul>' .
            '</div>';

        $Dropdown = new DropdownHelper(new View());

        $result = $Dropdown->create('My dropdown')
            ->link('First link', '#first')
            ->link('Second link', '#second')
            ->render();

        $this->assertSame($expected, $result);
    }

    /**
     * @link \Cake\Essentials\View\Helper\DropdownHelper::render()
     */
    #[Test]
    public function testRenderWithoutHavingCalledTheCreateMethod(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('The opening link has not been set, probably the `create()` method was not called previously.');
        $this->Dropdown->render();
    }

    /**
     * @link \Cake\Essentials\View\Helper\DropdownHelper::render()
     */
    #[Test]
    public function testRenderWithoutHavingCalledLinkMethods(): void
    {
        $Dropdown = new DropdownHelper(new View());
        $Dropdown->create(title: 'My dropdown');

        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Dropdown links have not been set');
        $Dropdown->render();
    }

    /**
     * @link \Cake\Essentials\View\Helper\DropdownHelper::__toString()
     */
    #[Test]
    public function testMagicToString(): void
    {
        $Dropdown = new DropdownHelper(new View());
        $Dropdown = $Dropdown->create('My dropdown')
            ->link('First link', '#first')
            ->link('Second link', '#second');

        $Copy = clone $Dropdown;

        $result = (string)$Dropdown;
        $this->assertNotEmpty($result);
        $this->assertSame($result, $Copy->render());
    }
}
