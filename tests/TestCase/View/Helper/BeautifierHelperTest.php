<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use Cake\Essentials\View\Helper\BeautifierHelper;
use Cake\Essentials\View\Helper\HtmlHelper;
use Cake\Essentials\View\View;
use Cake\TestSuite\TestCase;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * BeautifierHelperTest.
 */
#[CoversClass(BeautifierHelper::class)]
#[UsesClass(HtmlHelper::class)]
class BeautifierHelperTest extends TestCase
{
    /**
     * @var \Cake\Essentials\View\Helper\BeautifierHelper
     */
    protected BeautifierHelper $Beautifier;

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
        $this->Html = $this->createPartialMock(HtmlHelper::class, ['addPopover', 'addTooltip', 'icon']);

        $this->Beautifier = new BeautifierHelper(new View());
        $this->Beautifier->getView()->helpers()->set('Html', $this->Html);
    }

    #[Test]
    #[TestWith(['First<br />Second'])]
    #[TestWith([['First', 'Second']])]
    public function testQuestionPopover(string|array $popover): void
    {
        $this->Html
            ->expects($this->once())
            ->method('addPopover')
            ->with(['popover' => $popover, 'class' => 'cursor-pointer']);

        $this->Html
            ->expects($this->once())
            ->method('icon')
            ->with('question-circle-fill', ['class' => 'opacity-75 text-body-tertiary']);

        $this->Beautifier->questionPopover(popover: $popover);
    }

    #[Test]
    public function testQuestionPopoverWithCustomClassAndIcon(): void
    {
        $this->Html
            ->expects($this->once())
            ->method('addPopover')
            ->with(['popover' => 'Text', 'class' => 'custom-class cursor-pointer']);

        $this->Html
            ->expects($this->once())
            ->method('icon')
            ->with('house');

        $this->Beautifier->questionPopover(popover: 'Text', options: ['icon' => 'house', 'class' => 'custom-class']);
    }

    #[Test]
    #[TestWith(['First<br />Second'])]
    #[TestWith([['First', 'Second']])]
    public function testQuestionTooltip(string|array $tooltip): void
    {
        $this->Html
            ->method('addPopover')
            ->willReturnArgument(0);

        $this->Html
            ->expects($this->once())
            ->method('addTooltip')
            ->with(['tooltip' => $tooltip, 'class' => 'cursor-pointer']);

        $this->Html
            ->expects($this->once())
            ->method('icon')
            ->with('question-circle-fill', ['class' => 'opacity-75 text-body-tertiary']);

        $this->Beautifier->questionTooltip(tooltip: $tooltip);
    }

    #[Test]
    public function testQuestionTooltipWithCustomClassAndIcon(): void
    {
        $this->Html
            ->method('addPopover')
            ->willReturnArgument(0);

        $this->Html
            ->expects($this->once())
            ->method('addTooltip')
            ->with(['tooltip' => 'Text', 'class' => 'custom-class cursor-pointer']);

        $this->Html
            ->expects($this->once())
            ->method('icon')
            ->with('house');

        $this->Beautifier->questionTooltip(tooltip: 'Text', options: ['icon' => 'house', 'class' => 'custom-class']);
    }
}
