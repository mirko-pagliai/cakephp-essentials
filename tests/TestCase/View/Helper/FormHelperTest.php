<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use Cake\Essentials\View\Helper\FormHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

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
    public function testPostLink(): void
    {
        $expectedEnd = '<i class="fas fa-home"></i> Title</a>';
        $result = $this->Form->postLink(title: 'Title', url: '#url', options: ['icon' => 'home']);
        $this->assertStringEndsWith($expectedEnd, $result);
    }
}
