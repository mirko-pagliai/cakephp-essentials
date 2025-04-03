<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use Cake\Essentials\View\Helper\FormHelper;
use Cake\Essentials\View\Helper\HtmlHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * FormHelperTest.
 */
#[CoversClass(FormHelper::class)]
#[UsesClass(HtmlHelper::class)]
class FormHelperTest extends TestCase
{
    /**
     * @var \Cake\Essentials\View\Helper\FormHelper
     */
    protected FormHelper $Form;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->Form = new FormHelper(new View());
    }

    #[Test]
    public function testButton(): void
    {
        $expected = '<button class="btn btn-primary" type="submit"><i class="bi bi-home"></i> My button</button>';
        $result = $this->Form->button(title: 'My button', options: ['icon' => 'home']);
        $this->assertSame($expected, $result);
    }

    #[Test]
    #[TestWith(['btn btn-primary'])]
    #[TestWith(['btn btn-success', ['type' => 'submit']])]
    #[TestWith(['btn btn-primary', ['type' => 'reset']])]
    #[TestWith(['custom-class btn btn-secondary', ['class' => 'custom-class', 'type' => 'submit']])]
    public function testButtonClassesAndType(string $expectedClass, array $options = []): void
    {
        $result = $this->Form->button(title: 'My button', options: $options);
        $this->assertStringContainsString('class="' . $expectedClass . '"', $result);
    }

    #[Test]
    #[TestWith(['<div id="myfield-help" class="form-text">A help text</div>', 'A help text'])]
    #[TestWith(['<div id="myfield-help" class="form-text"><div>First</div><div>Second</div></div>', ['First', 'Second']])]
    public function testControlWithHelp(string $expectedContains, string|array $help): void
    {
        $result = $this->Form->control(fieldName: 'myField', options: compact('help'));
        $this->assertStringContainsString($expectedContains, $result);
    }

    #[Test]
    public function testControlDateWithDefaultAsNow(): void
    {
        $result = $this->Form->control(fieldName: 'myField', options: ['default' => 'now', 'type' => 'date']);
        $this->assertMatchesRegularExpression('/value="\d{4}\-\d{2}\-\d{2}"/', $result);
    }

    #[Test]
    public function testControlDatetimeWithDefaultAsNow(): void
    {
        $result = $this->Form->control(fieldName: 'myField', options: ['default' => 'now', 'type' => 'datetime']);
        $this->assertMatchesRegularExpression('/value="\d{4}\-\d{2}\-\d{2}T\d{2}:\d{2}:00"/', $result);
    }

    #[Test]
    public function testControlSwitchType(): void
    {
        $expected = [
            'div' => ['class' => 'form-check form-switch checkbox'],
            ['input' => ['type' => 'hidden', 'name' => 'myField', 'value' => '0']],
            ['input' => ['type' => 'checkbox', 'name' => 'myField', 'value' => '1', 'id' => 'myfield', 'class' => 'form-check-input']],
            'label' => ['class' => 'form-check-label', 'for' => 'myfield'],
            'My Field',
            '/label',
            '/div',
        ];
        $result = $this->Form->control(fieldName: 'myField', options: ['type' => 'switch']);
        $this->assertHtml($expected, $result);
    }

    #[Test]
    public function testControlDateWithAppendNowButton(): void
    {
        $expected = '<button class="btn btn-primary btn-sm text-nowrap" onclick="javascript:event.preventDefault(); $(this).prev(&#039;input&#039;).val(moment(new Date()).format(&#039;YYYY-MM-DD&#039;))" type="button"><i class="bi bi-clock"></i> Today</button>';
        $result = $this->Form->control(fieldName: 'myField', options: [
            'appendNowButton' => true,
            'type' => 'date',
        ]);
        $this->assertStringContainsString($expected, $result);
    }

    #[Test]
    public function testControlDatetimeWithAppendNowButton(): void
    {
        $expected = '<button class="btn btn-primary btn-sm text-nowrap" onclick="javascript:event.preventDefault(); $(this).prev(&#039;input&#039;).val(moment(new Date()).format(&#039;YYYY-MM-DDTHH:mm&#039;))" type="button"><i class="bi bi-clock"></i> Now</button>';
        $result = $this->Form->control(fieldName: 'myField', options: [
            'appendNowButton' => true,
            'type' => 'datetime',
        ]);
        $this->assertStringContainsString($expected, $result);
    }

    #[Test]
    #[TestWith(['<input type="datetime-local" name="datetime" step="60" class="form-control" value="">'])]
    #[TestWith(['<input type="datetime-local" name="datetime" class="form-control" value="">', ['step' => null]])]
    public function testDatetime(string $expectedDatetime, array $options = []): void
    {
        $result = $this->Form->datetime(fieldName: 'datetime', options: $options);
        $this->assertSame($expectedDatetime, $result);
    }

    #[Test]
    public function testPostLink(): void
    {
        $expected = [
            'form' => ['name', 'method' => 'post', 'style' => 'display:none;', 'action' => '#url'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/form',
            'a' => ['href' => '#', 'onclick', 'class' => 'text-decoration-none', 'title' => 'Title'],
            'i' => ['class' => 'bi bi-home'],
            '/i',
            'Title',
            '/a',
        ];
        $result = $this->Form->postLink(title: 'Title', url: '#url', options: ['icon' => 'home']);
        $this->assertHtml($expected, $result);
    }

    #[Test]
    public function testRadio(): void
    {
        $result = $this->Form->radio(fieldName: 'myField', options: ['first' => '<em>First</em>', 'second' => '<strong>Second</strong>']);
        $this->assertStringContainsString('<label class="form-check-label" for="myfield-first"><em>First</em></label>', $result);
        $this->assertStringContainsString('<label class="form-check-label" for="myfield-second"><strong>Second</strong></label>', $result);
    }

    #[Test]
    public function testSelectContainsEmptyValue(): void
    {
        $result = $this->Form->select(fieldName: 'select', options: []);
        $this->assertStringContainsString('<option value="">', $result);
        $this->assertStringContainsString('select an option', $result);
    }

    #[Test]
    #[TestWith([['default' => 'a']])]
    #[TestWith([['value' => 'a']])]
    #[TestWith([['multiple' => true]])]
    #[TestWith([['empty' => false]])]
    #[TestWith([['empty' => null]])]
    public function testSelectNotContainsEmptyValue(array $options): void
    {
        $result = $this->Form->select(fieldName: 'select', attributes: $options);
        $this->assertStringNotContainsString('<option value="">', $result);
        $this->assertStringNotContainsString('select an option', $result);
    }

    #[Test]
    #[TestWith(['<input type="submit" class="btn btn-success" value="">'])]
    #[TestWith(['<input type="submit" class="btn btn-primary" value="">', ['class' => 'btn btn-primary']])]
    #[TestWith(['<input type="submit" class="custom-class btn btn-secondary" value="">', ['class' => 'custom-class']])]
    public function testSubmit(string $expectedButton, array $options = []): void
    {
        $expectedButton = '<div class="submit">' . $expectedButton . '</div>';
        $result = $this->Form->submit(caption: '', options: $options);
        $this->assertSame($expectedButton, $result);
    }
}
