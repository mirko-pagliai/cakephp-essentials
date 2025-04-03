<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use Cake\Essentials\View\Helper\AlertHelper;
use Cake\Essentials\View\Helper\FlashHelper;
use Cake\Essentials\View\Helper\HtmlHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Generator;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\Attributes\UsesClass;
use UnexpectedValueException;

/**
 * FlashHelperTest.
 */
#[CoversClass(FlashHelper::class)]
#[UsesClass(AlertHelper::class)]
#[UsesClass(HtmlHelper::class)]
class FlashHelperTest extends TestCase
{
    /**
     * @var \Cake\Essentials\View\Helper\FlashHelper
     */
    protected FlashHelper $Flash;

    /**
     * @inheritDoc
     */
    #[Override]
    protected function setUp(): void
    {
        $View = new View();
        $View->helpers()->set('Html', new HtmlHelper($View));
        $View->helpers()->set('Alert', new AlertHelper($View));

        $this->Flash = new FlashHelper($View);
    }

    #[Test]
    public function testRender(): void
    {
        $this->Flash->getView()->getRequest()->getSession()->write('Flash', [
            'flash' => [
                'message' => 'My flash message',
                'key' => 'flash',
                'element' => 'flash/success',
                'params' => [
                    'class' => 'my-custom-class',
                    'icon' => ['name' => 'house', 'class' => 'fs-6'],
                ],
            ],
        ]);
        $expected = '<div class="my-custom-class d-print-none alert alert-success border-0 d-flex align-items-baseline" role="alert">' .
            '<div class="alert-icon me-2"><i class="fs-6 bi bi-house"></i></div>' .
            '<div class="alert-text">My flash message</div>' .
            '</div>';
        $result = $this->Flash->render();
        $this->assertSame($expected, $result);
    }

    #[Test]
    public function testRenderOnEmptyFlashMessages(): void
    {
        $this->assertNull($this->Flash->render());
    }

    #[Test]
    #[TestWith(['alert-primary', 'default'])]
    #[TestWith(['alert-dark', 'dark'])]
    #[TestWith(['alert-danger', 'error'])]
    #[TestWith(['alert-info', 'info'])]
    #[TestWith(['alert-warning', 'warning'])]
    public function testRenderDefaultClasses(string $expectedClass, string $type): void
    {
        $this->Flash->getView()->getRequest()->getSession()->write('Flash', [
            'flash' => [
                'message' => '',
                'key' => 'flash',
                'element' => 'flash/' . $type,
                'params' => [],
            ],
        ]);
        /** @var non-empty-string $result */
        $result = $this->Flash->render();
        $this->assertStringContainsString($expectedClass, $result);
    }

    public static function providerTestRenderDefaultIcons(): Generator
    {
        $View = new View();
        $View->helpers()->set('Html', new HtmlHelper($View));
        $Alert = new AlertHelper($View);

        /** @var array<string, string> $defaultIcons */
        $defaultIcons = $Alert->getConfigOrFail('icon');

        yield [$Alert->Html->icon($defaultIcons['success']), 'success'];
        yield [$Alert->Html->icon($defaultIcons['danger']), 'error'];
        yield [$Alert->Html->icon($defaultIcons['warning']), 'warning'];
    }

    #[Test]
    #[DataProvider('providerTestRenderDefaultIcons')]
    public function testRenderDefaultIcons(string $expectedIconClasses, string $type): void
    {
        $this->Flash->getView()->getRequest()->getSession()->write('Flash', [
            'flash' => [
                'message' => '',
                'key' => 'flash',
                'element' => 'flash/' . $type,
                'params' => [],
            ],
        ]);
        /** @var non-empty-string $result */
        $result = $this->Flash->render();
        $this->assertStringContainsString($expectedIconClasses, $result);
    }

    #[Test]
    public function testRenderWithInvalidKey(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Value for flash setting key "invalidKey" must be an array');
        $this->Flash->getView()->getRequest()->getSession()->write('Flash', ['invalidKey' => 'invalid']);
        $this->Flash->render('invalidKey');
    }
}
