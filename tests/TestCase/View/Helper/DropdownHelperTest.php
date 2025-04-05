<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

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
#[UsesClass(HtmlHelper::class)]
class DropdownHelperTest extends TestCase
{
    /**
     * @var \Cake\Essentials\View\Helper\DropdownHelper
     */
    protected DropdownHelper $Dropdown;

    /**
     * @inheritDoc
     */
    #[Override]
    protected function setUp(): void
    {
        $this->Dropdown = new DropdownHelper(new View());
    }

    #[Test]
    public function testCreate(): void
    {
        $HtmlHelper = $this->createPartialMock(HtmlHelper::class, ['link']);
        $HtmlHelper
            ->expects($this->once())
            ->method('link')
            ->with('My dropdown main link', '#', [
                'class' => 'my-custom-class dropdown-toggle',
                'aria-expanded' => 'false',
                'data-bs-toggle' => 'dropdown',
                'role' => 'button',
            ]);

        $this->Dropdown->getView()->helpers()->set('Html', $HtmlHelper);

        $this->Dropdown->create(title: 'My dropdown main link', options: ['class' => 'my-custom-class']);
    }

    #[Test]
    public function testLink(): void
    {
        $HtmlHelper = $this->createPartialMock(HtmlHelper::class, ['link']);
        $HtmlHelper
            ->expects($this->once())
            ->method('link')
            ->with(
                'My link',
                '#',
                ['class' => 'my-custom-class dropdown-item']
            );

        $this->Dropdown->getView()->helpers()->set('Html', $HtmlHelper);

        $this->Dropdown->link(
            title: 'My link',
            url: '#',
            options: ['class' => 'my-custom-class']
        );
    }

    #[Test]
    public function testLinkFromPath(): void
    {
        $HtmlHelper = $this->createPartialMock(HtmlHelper::class, ['link']);
        $HtmlHelper
            ->expects($this->once())
            ->method('link')
            ->with(
                'My link from path',
                ['_path' => 'Users::index', '?' => ['k' => 'v']],
                ['class' => 'my-custom-class dropdown-item']
            );

        $this->Dropdown->getView()->helpers()->set('Html', $HtmlHelper);

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
        $FormHelper = $this->createPartialMock(FormHelper::class, ['postLink']);
        $FormHelper
            ->expects($this->once())
            ->method('postLink')
            ->with(
                'My post link',
                '#',
                ['class' => 'my-custom-class dropdown-item']
            );

        $this->Dropdown->getView()->helpers()->set('Form', $FormHelper);

        $this->Dropdown->postLink(
            title: 'My post link',
            url: '#',
            options: ['class' => 'my-custom-class']
        );
    }
}
