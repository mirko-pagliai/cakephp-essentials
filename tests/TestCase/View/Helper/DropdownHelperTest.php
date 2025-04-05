<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use BadMethodCallException;
use Cake\Essentials\View\Helper\DropdownHelper;
use Cake\Essentials\View\Helper\FormHelper;
use Cake\Essentials\View\Helper\HtmlHelper;
use Cake\Essentials\View\View;
use Cake\TestSuite\TestCase;
use Override;
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
    /**
     * @var \Cake\Essentials\View\Helper\DropdownHelper
     */
    protected DropdownHelper $Dropdown;

    /**
     * @var \Cake\Essentials\View\Helper\FormHelper&\PHPUnit\Framework\MockObject\MockObject
     */
    protected FormHelper $Form;

    /**
     * @var \Cake\Essentials\View\Helper\HtmlHelper&\PHPUnit\Framework\MockObject\MockObject
     */
    protected HtmlHelper $Html;

    /**
     * {@inheritDoc}
     *
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Override]
    protected function setUp(): void
    {
        $this->Form = $this->createPartialMock(FormHelper::class, ['postLink']);
        $this->Html = $this->createPartialMock(HtmlHelper::class, ['link']);

        $View = new View();
        $View->helpers()->set('Html', $this->Html);
        $View->helpers()->set('Form', $this->Form);

        $this->Dropdown = new DropdownHelper($View);
    }

    #[Test]
    public function testCreate(): void
    {
        $this->Html
            ->expects($this->once())
            ->method('link')
            ->with('My dropdown main link', '#', [
                'class' => 'my-custom-class dropdown-toggle',
                'aria-expanded' => 'false',
                'data-bs-toggle' => 'dropdown',
                'role' => 'button',
            ]);

        $this->Dropdown->create(title: 'My dropdown main link', options: ['class' => 'my-custom-class']);
    }

    #[Test]
    public function testLink(): void
    {
        $this->Html
            ->expects($this->once())
            ->method('link')
            ->with(
                'My link',
                '#',
                ['class' => 'my-custom-class dropdown-item']
            );

        $this->Dropdown->link(
            title: 'My link',
            url: '#',
            options: ['class' => 'my-custom-class']
        );
    }

    #[Test]
    public function testLinkFromPath(): void
    {
        $this->Html
            ->expects($this->once())
            ->method('link')
            ->with(
                'My link from path',
                ['_path' => 'Users::index', '?' => ['k' => 'v']],
                ['class' => 'my-custom-class dropdown-item']
            );

        $this->Dropdown->linkFromPath(
            title: 'My link from path',
            path: 'Users::index',
            params: ['?' => ['k' => 'v']],
            options: ['class' => 'my-custom-class']
        );
    }

    #[Test]
    public function testPostLink(): void
    {
        $this->Form
            ->expects($this->once())
            ->method('postLink')
            ->with(
                'My post link',
                '#',
                ['class' => 'my-custom-class dropdown-item']
            );

        $this->Dropdown->postLink(
            title: 'My post link',
            url: '#',
            options: ['class' => 'my-custom-class']
        );
    }

    #[Test]
    public function testHasLinks(): void
    {
        $Dropdown = new DropdownHelper(new View());

        $this->assertFalse($Dropdown->hasLinks());

        $Dropdown->link('My link', '#');

        $this->assertTrue($Dropdown->hasLinks());
    }

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

    #[Test]
    public function testRenderWithoutHavingCalledTheCreateMethod(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('The opening link has not been set, probably the `create()` method was not called previously.');
        $this->Dropdown->render();
    }

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
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    public function testMagicToString(): void
    {
        $Dropdown = $this->createPartialMock(DropdownHelper::class, ['render']);
        $Dropdown
            ->expects($this->once())
            ->method('render')
            ->with([]);

        (string)$Dropdown;
    }
}
