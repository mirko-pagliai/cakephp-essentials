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

        $this->Beautifier = new BeautifierHelper(new View());
    }

    /**
     * @param string $expected
     * @param array<string, mixed> $options
     *
     * @link \Cake\Essentials\View\Helper\BeautifierHelper::ipAddress()
     */
    #[Test]
    #[TestWith(['<span><i class="small text-body-secondary bi bi-globe-americas"></i> <code>127.0.0.1</code></span>'])]
    #[TestWith(['<span class="custom-class"><i class="bi bi-house"></i> <code>127.0.0.1</code></span>', ['icon' => 'house', 'class' => 'custom-class']])]
    public function testIpAddress(string $expected, array $options = []): void
    {
        $result = $this->Beautifier->ipAddress('127.0.0.1', $options);
        $this->assertSame($expected, $result);
    }

    /**
     * Tests for the `ipAddress()` method with a custom icon from the config.
     *
     * @link \Cake\Essentials\View\Helper\BeautifierHelper::ipAddress()
     */
    #[Test]
    public function testIpAddressWithCustomIconFromConfig(): void
    {
        $this->Beautifier->setConfig('icon.ipAddress', ['name' => 'globe', 'prefix' => 'fas', 'namespace' => 'fa']);

        $result = $this->Beautifier->ipAddress('127.0.0.1');
        $this->assertStringContainsString('<i class="small text-body-secondary fa fas-globe"></i>', $result);
    }

    /**
     * @param array<string>|string $popover
     *
     * @link \Cake\Essentials\View\Helper\BeautifierHelper::questionPopover()
     */
    #[Test]
    #[TestWith(['First<br />Second'])]
    #[TestWith([['First', 'Second']])]
    public function testQuestionPopover(array|string $popover): void
    {
        $expected = [
            'span' => [
                'data-bs-content' => 'First&lt;br /&gt;Second',
                'data-bs-html' => 'true',
                'data-bs-trigger' => 'focus',
                'data-bs-toggle' => 'popover',
                'role' => 'button',
                'tabindex' => '0',
            ],
            'i' => ['class' => 'opacity-75 text-body-tertiary bi bi-question-circle-fill'],
            '/i',
            '/span',
        ];
        $result = $this->Beautifier->questionPopover(popover: $popover);
        $this->assertHtml($expected, $result);
    }

    /**
     * @link \Cake\Essentials\View\Helper\BeautifierHelper::questionPopover()
     */
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
                'class' => 'custom-class',
            ],
            'i' => ['class' => 'bi bi-house'],
            '/i',
            '/span',
        ];
        $result = $this->Beautifier->questionPopover(popover: 'Text', options: ['icon' => 'house', 'class' => 'custom-class']);
        $this->assertHtml($expected, $result);
    }

    /**
     * Tests for the `questionPopover()` method with a custom icon from the config.
     *
     * @link \Cake\Essentials\View\Helper\BeautifierHelper::questionPopover()
     */
    #[Test]
    public function testQuestionPopoverWithCustomIconFromConfig(): void
    {
        $this->Beautifier->setConfig('icon.question', ['name' => 'question', 'prefix' => 'fas', 'namespace' => 'fa']);

        $result = $this->Beautifier->questionPopover(popover: 'Text');
        $this->assertStringContainsString('<i class="opacity-75 text-body-tertiary fa fas-question"></i>', $result);
    }

    /**
     * @param array<string>|string $tooltip
     *
     * @link \Cake\Essentials\View\Helper\BeautifierHelper::questionTooltip()
     */
    #[Test]
    #[TestWith(['First<br />Second'])]
    #[TestWith([['First', 'Second']])]
    public function testQuestionTooltip(array|string $tooltip): void
    {
        $expected = [
            'span' => [
                'data-bs-html' => 'true',
                'data-bs-title' => 'First&lt;br /&gt;Second',
                'data-bs-trigger' => 'hover',
                'tooltip' => 'true',
            ],
            'i' => ['class' => 'opacity-75 text-body-tertiary bi bi-question-circle-fill'],
            '/i',
            '/span',
        ];
        $result = $this->Beautifier->questionTooltip(tooltip: $tooltip);
        $this->assertHtml($expected, $result);
    }

    /**
     * @link \Cake\Essentials\View\Helper\BeautifierHelper::questionTooltip()
     */
    #[Test]
    public function testQuestionTooltipWithCustomClassAndIcon(): void
    {
        $expected = [
            'span' => [
                'data-bs-html' => 'true',
                'data-bs-title' => 'Text',
                'data-bs-trigger' => 'hover',
                'class' => 'custom-class',
                'tooltip' => 'true',
            ],
            'i' => ['class' => 'bi bi-house'],
            '/i',
            '/span',
        ];
        $result = $this->Beautifier->questionTooltip(tooltip: 'Text', options: ['icon' => 'house', 'class' => 'custom-class']);
        $this->assertHtml($expected, $result);
    }

    /**
     * Tests for the `questionTooltip()` method with a custom icon from the config.
     *
     * @link \Cake\Essentials\View\Helper\BeautifierHelper::questionTooltip()
     */
    #[Test]
    public function testQuestionTooltipWithCustomIconFromConfig(): void
    {
        $this->Beautifier->setConfig('icon.question', ['name' => 'question', 'prefix' => 'fas', 'namespace' => 'fa']);

        $result = $this->Beautifier->questionTooltip(tooltip: 'Text');
        $this->assertStringContainsString('<i class="opacity-75 text-body-tertiary fa fas-question"></i>', $result);
    }

    /**
     * @link \Cake\Essentials\View\Helper\BeautifierHelper::userAgent()
     */
    #[Test]
    #[TestWith(['bi bi-question-circle-fill', 'Unknown agent'])]
    #[TestWith(['bi bi-android', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Mobile Safari/537.36'])]
    #[TestWith(['bi bi-ubuntu', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36'])]
    #[TestWith(['bi bi-apple', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5.1 Mobile/15E148 Safari/604.1'])]
    #[TestWith(['bi bi-apple', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 13_4_1) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5 Safari/605.1.15'])]
    #[TestWith(['bi bi-windows', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36'])]
    public function testUserAgent(string $expectedIcon, string $userAgent): void
    {
        $expected = '<span><i class="me-1 small text-body-secondary ' . $expectedIcon . '"></i> <code>' . $userAgent . '</code></span>';
        $result = $this->Beautifier->userAgent($userAgent);
        $this->assertSame($expected, $result);
    }

    /**
     * @link \Cake\Essentials\View\Helper\BeautifierHelper::userAgent()
     */
    #[Test]
    public function testUserAgentWithCustomClassAndIcon(): void
    {
        $expected = '<span class="custom-class"><i class="bi bi-house"></i> <code>Unknown agent</code></span>';
        $result = $this->Beautifier->userAgent('Unknown agent', ['class' => 'custom-class', 'icon' => 'house']);
        $this->assertSame($expected, $result);
    }

    /**
     * Tests for the `userAgent()` method with a custom icon from the config.
     *
     * @link \Cake\Essentials\View\Helper\BeautifierHelper::userAgent()
     */
    #[Test]
    #[TestWith(['unknownDevice', 'Unknown agent'])]
    #[TestWith(['android', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Mobile Safari/537.36'])]
    #[TestWith(['linux', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36'])]
    #[TestWith(['apple', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5.1 Mobile/15E148 Safari/604.1'])]
    #[TestWith(['apple', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 13_4_1) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5 Safari/605.1.15'])]
    #[TestWith(['windows', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36'])]
    public function testUserAgentWithCustomIconFromConfig(string $expectedDeviceName, string $userAgent): void
    {
        $this->Beautifier->setConfig('icon.' . $expectedDeviceName, ['name' => $expectedDeviceName, 'prefix' => 'fa', 'namespace' => 'fas']);

        $result = $this->Beautifier->userAgent($userAgent);
        $this->assertStringContainsString("<i class=\"me-1 small text-body-secondary fas fa-{$expectedDeviceName}\"></i>", $result);
    }
}
