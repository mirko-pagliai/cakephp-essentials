<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use Cake\Essentials\View\Helper\FormHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * FormHelperTest.
 */
#[CoversClass(FormHelper::class)]
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
    #[TestWith(['btn btn-primary'])]
    #[TestWith(['btn btn-success', ['type' => 'submit']])]
    #[TestWith(['btn btn-primary', ['type' => 'reset']])]
    #[TestWith(['custom-class btn btn-secondary', ['class' => 'custom-class', 'type' => 'submit']])]
    public function testButton(string $expectedClass, array $options = []): void
    {
        $result = $this->Form->button(title: 'My button', options: $options);
        $this->assertStringContainsString('class="' . $expectedClass . '"', $result);
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
        $expectedEnd = '<i class="fas fa-home"></i> Title</a>';
        $result = $this->Form->postLink(title: 'Title', url: '#url', options: ['icon' => 'home']);
        $this->assertStringEndsWith($expectedEnd, $result);
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
}
