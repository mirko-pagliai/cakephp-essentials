<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use ArgumentCountError;
use BadMethodCallException;
use Cake\Essentials\View\Helper\AlertHelper;
use Cake\Essentials\View\Helper\HtmlHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Generator;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * AlertHelperTest.
 */
#[CoversClass(AlertHelper::class)]
#[UsesClass(HtmlHelper::class)]
class AlertHelperTest extends TestCase
{
    protected AlertHelper $Alert;

    /**
     * @var \Mockery\MockInterface&\Cake\Essentials\View\Helper\HtmlHelper
     */
    protected HtmlHelper $Html;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        $View = new View();

        /** @var \Mockery\MockInterface&\Cake\Essentials\View\Helper\HtmlHelper $HtmlHelper */
        $HtmlHelper = Mockery::mock(HtmlHelper::class . '[link]', [$View]);
        $this->Html = $HtmlHelper;

        $this->Alert = new AlertHelper($View);
        $this->Alert->getView()->helpers()->set('Html', $this->Html);
    }

    /**
     * @param array<string>|string $text
     * @param array<string, mixed> $options
     *
     * @link \Cake\Essentials\View\Helper\AlertHelper::__call()
     */
    #[Test]
    #[TestWith(['Text'])]
    #[TestWith(['Text', ['class' => 'custom-class']])]
    #[TestWith([['First line', 'Second line']])]
    public function testMagicCallMethod(array|string $text, array $options = []): void
    {
        $expected = $this->Alert->alert(type: 'success', text: $text, options: $options);

        $result = $this->Alert->success($text, $options);
        $this->assertSame($expected, $result);

        $result = $this->Alert->success(text: $text, options: $options);
        $this->assertSame($expected, $result);
    }

    /**
     * @link \Cake\Essentials\View\Helper\AlertHelper::__call()
     */
    #[Test]
    public function testMagicCallMethodTooFewArguments(): void
    {
        $this->expectException(ArgumentCountError::class);
        // @phpstan-ignore-next-line
        $this->Alert->success();
    }

    /**
     * @link \Cake\Essentials\View\Helper\AlertHelper::__call()
     */
    #[Test]
    public function testMagicCallMethodTooManyArguments(): void
    {
        $this->expectException(ArgumentCountError::class);
        $this->expectExceptionMessage('Too many arguments for `' . $this->Alert::class . '::success()`, 3 passed and at most 2 expected.');
        // @phpstan-ignore-next-line
        $this->Alert->success('Text', [], 'Third');
    }

    /**
     * @link \Cake\Essentials\View\Helper\AlertHelper::__call()
     */
    #[Test]
    public function testMagicCallMethodWithANoExistingMethod(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Method `' . $this->Alert::class . '::noExistingMethod()` does not exist.');
        // @phpstan-ignore-next-line
        $this->Alert->noExistingMethod();
    }

    /**
     * @param string $expectedAlert
     * @param array<string>|string $text
     * @param array<string, mixed> $options
     *
     * @link \Cake\Essentials\View\Helper\AlertHelper::alert()
     */
    #[Test]
    #[TestWith(['<div role="alert" class="alert alert-dark border-0">Text</div>', 'Text'])]
    #[TestWith(['<div role="alert" class="alert alert-dark border-0">First<br />Second</div>', ['First', 'Second']])]
    #[TestWith(['<div class="custom-class alert alert-dark border-0" role="alert">Text</div>', 'Text', ['class' => 'custom-class']])]
    public function testAlert(string $expectedAlert, array|string $text, array $options = []): void
    {
        $result = $this->Alert->alert(type: 'dark', text: $text, options: $options);
        $this->assertSame($expectedAlert, $result);
    }

    /**
     * @param string $expectedIcon
     * @param array<string>|string $icon
     *
     * @link \Cake\Essentials\View\Helper\AlertHelper::alert()
     */
    #[Test]
    #[TestWith(['<i class="bi bi-house"></i>', 'house'])]
    #[TestWith(['<i class="fs-4 bi bi-house"></i>', ['name' => 'house', 'class' => 'fs-4']])]
    public function testAlertWithCustomIcons(string $expectedIcon, array|string $icon): void
    {
        $expected = '<div role="alert" class="alert alert-dark border-0 d-flex align-items-baseline">' .
            '<div class="alert-icon me-2">' . $expectedIcon . '</div>' .
            '<div class="alert-text">Text</div>' .
            '</div>';
        $result = $this->Alert->alert(type: 'dark', text: 'Text', options: compact('icon'));
        $this->assertSame($expected, $result);
    }

    /**
     * @link \Cake\Essentials\View\Helper\AlertHelper::alert()
     */
    #[Test]
    #[TestWith(['<i class="bi bi-exclamation-triangle-fill"></i>', 'danger'])]
    #[TestWith(['<i class="bi bi-check-circle-fill"></i>', 'success'])]
    #[TestWith(['<i class="bi bi-exclamation-triangle-fill"></i>', 'warning'])]
    public function testAlertWithDefaultIcons(string $expectedIcon, string $alertType): void
    {
        $expected = '<div role="alert" class="alert alert-' . $alertType . ' border-0 d-flex align-items-baseline">' .
            '<div class="alert-icon me-2">' . $expectedIcon . '</div>' .
            '<div class="alert-text">Text</div>' .
            '</div>';

        $result = $this->Alert->alert(type: $alertType, text: 'Text');
        $this->assertSame($expected, $result);
    }

    /**
     * @link \Cake\Essentials\View\Helper\AlertHelper::alert()
     */
    #[Test]
    public function testAlertWithDisabledIcon(): void
    {
        $result = $this->Alert->alert(type: 'warning', text: 'Text', options: ['icon' => false]);
        $this->assertStringNotContainsString('<i class=', $result);
    }

    public static function providerTestAlertRewritesDefaultIcons(): Generator
    {
        $Alert = new AlertHelper(new View());
        /** @var array<string, string> $defaultIconConfig */
        $defaultIconConfig = $Alert->getConfigOrFail('icon');

        foreach (array_keys($defaultIconConfig) as $alertTypeWithDefaultIcon) {
            yield [$alertTypeWithDefaultIcon];
        }
    }

    /**
     * @link \Cake\Essentials\View\Helper\AlertHelper::alert()
     */
    #[Test]
    #[DataProvider('providerTestAlertRewritesDefaultIcons')]
    public function testAlertRewritesDefaultIcons(string $alertType): void
    {
        $expected = '<i class="bi bi-house"></i>';

        //Rewrites the icon using the `icon` option
        $result = $this->Alert->alert(type: $alertType, text: 'Text', options: ['icon' => 'house']);
        $this->assertStringContainsString($expected, $result);

        //Rewrites the icon using `setConfig()`
        $result = $this->Alert
            ->setConfig('icon.' . $alertType, 'house')
            ->alert(type: $alertType, text: 'My text');
        $this->assertStringContainsString($expected, $result);
    }

    /**
     * @link \Cake\Essentials\View\Helper\AlertHelper::link()
     */
    #[Test]
    public function testLink(): void
    {
        $this->Html
            ->shouldReceive('link')
            ->with('Title', '#example', ['class' => 'custom-class alert-link'])
            ->once();

        $this->Alert->link('Title', '#example', ['class' => 'custom-class']);
    }

    /**
     * @link \Cake\Essentials\View\Helper\AlertHelper::linkFromPath()
     */
    #[Test]
    public function testLinkFromPath(): void
    {
        $this->Html
            ->shouldReceive('link')
            ->once()
            ->with(
                'Title',
                ['_path' => 'Users::view', '?' => ['k' => 'v']],
                ['class' => 'custom-class alert-link'],
            )
            ->shouldReceive('link')
            ->once()
            ->with(
                'Title',
                ['_path' => 'Users::view', 1],
                ['class' => 'custom-class alert-link'],
            );

        $this->Alert->linkFromPath(
            title: 'Title',
            path: 'Users::view',
            params: ['?' => ['k' => 'v']],
            options: ['class' => 'custom-class'],
        );

        $this->Alert->linkFromPath(
            title: 'Title',
            path: 'Users::view',
            params: [1],
            options: ['class' => 'custom-class'],
        );
    }
}
