<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use Cake\Essentials\View\Helper\AlertHelper;
use Cake\Essentials\View\Helper\FlashHelper;
use Cake\Essentials\View\Helper\HtmlHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use UnexpectedValueException;

/**
 * FlashHelperTest.
 */
#[CoversClass(FlashHelper::class)]
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

    #[Test]
    #[TestWith(['<i class="bi bi-check-circle-fill"></i>', 'success'])]
    #[TestWith(['<i class="bi bi-exclamation-triangle"></i>', 'error'])]
    #[TestWith(['<i class="bi bi-exclamation-triangle"></i>', 'warning'])]
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
