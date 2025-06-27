<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use Cake\Essentials\View\Helper\BeautifierHelper;
use Cake\Essentials\View\Helper\HtmlHelper;
use Cake\Essentials\View\View;
use Cake\TestSuite\TestCase;
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
    protected BeautifierHelper $Beautifier;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->Beautifier ??= new BeautifierHelper(new View());
    }

    #[Test]
    #[TestWith(['First<br />Second'])]
    #[TestWith([['First', 'Second']])]
    public function testQuestionPopover(string|array $popover): void
    {
        $expected = [
            'span' => [
                'data-bs-content' => 'First&lt;br /&gt;Second',
                'data-bs-html' => 'true',
                'data-bs-trigger' => 'focus',
                'data-bs-toggle' => 'popover',
                'role' => 'button',
                'tabindex' => '0',
                'class' => 'cursor-pointer',
            ],
            'i' => ['class' => 'opacity-75 text-body-tertiary bi bi-question-circle-fill'],
            '/i',
            '/span',
        ];
        $result = $this->Beautifier->questionPopover(popover: $popover);
        $this->assertHtml($expected, $result);
    }

    #[Test]
    public function testQuestionPopoverWithCustomClassAndIcon(): void
    {
        $expected = [
            'span' => [
                'data-bs-content' => 'Text',
                'data-bs-html' => 'true',
                'data-bs-trigger' => 'focus',
                'data-bs-toggle' => 'popover',
                'role' => 'button',
                'tabindex' => '0',
                'class' => 'custom-class cursor-pointer',
            ],
            'i' => ['class' => 'bi bi-house'],
            '/i',
            '/span',
        ];
        $result = $this->Beautifier->questionPopover(popover: 'Text', options: ['icon' => 'house', 'class' => 'custom-class']);
        $this->assertHtml($expected, $result);
    }

    #[Test]
    #[TestWith(['First<br />Second'])]
    #[TestWith([['First', 'Second']])]
    public function testQuestionTooltip(string|array $tooltip): void
    {
        $expected = [
            'span' => [
                'data-bs-html' => 'true',
                'data-bs-title' => 'First&lt;br /&gt;Second',
                'data-bs-toggle' => 'tooltip',
                'class' => 'cursor-pointer',
            ],
            'i' => ['class' => 'opacity-75 text-body-tertiary bi bi-question-circle-fill'],
            '/i',
            '/span',
        ];
        $result = $this->Beautifier->questionTooltip(tooltip: $tooltip);
        $this->assertHtml($expected, $result);
    }

    #[Test]
    public function testQuestionTooltipWithCustomClassAndIcon(): void
    {
        $expected = [
            'span' => [
                'data-bs-html' => 'true',
                'data-bs-title' => 'Text',
                'data-bs-toggle' => 'tooltip',
                'class' => 'custom-class cursor-pointer',
            ],
            'i' => ['class' => 'bi bi-house'],
            '/i',
            '/span',
        ];
        $result = $this->Beautifier->questionTooltip(tooltip: 'Text', options: ['icon' => 'house', 'class' => 'custom-class']);
        $this->assertHtml($expected, $result);
    }
}
