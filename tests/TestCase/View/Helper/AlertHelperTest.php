<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use BadMethodCallException;
use Cake\Essentials\TestSuite\TestCase;
use Cake\Essentials\View\Helper\AlertHelper;
use Cake\Essentials\View\Helper\HtmlHelper;
use Cake\View\View;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

/**
 * AlertHelperTest.
 */
#[CoversClass(AlertHelper::class)]
class AlertHelperTest extends TestCase
{
    /**
     * @var \Cake\Essentials\View\Helper\AlertHelper
     */
    protected AlertHelper $Alert;

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
    public function setUp(): void
    {
        $this->Html = $this->createPartialMock(HtmlHelper::class, ['link']);

        $this->Alert = new AlertHelper(new View());
        $this->Alert->getView()->helpers()->set('Html', $this->Html);
    }

    #[Test]
    public function testMagicCallMethodWithANoExistingMethod(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Method `' . $this->Alert::class . '::noExistingMethod()` does not exist.');
        // @phpstan-ignore-next-line
        $this->Alert->noExistingMethod();
    }

    #[Test]
    public function testLink(): void
    {
        $this->Html
            ->expects($this->once())
            ->method('link')
            ->with('Title', '#example', ['class' => 'custom-class alert-link']);

        $this->Alert->link('Title', '#example', ['class' => 'custom-class']);
    }

    #[Test]
    public function testLinkFromPath(): void
    {
        $this->Html
            ->expects($this->once())
            ->method('link')
            ->with('Title', ['_path' => 'Users::view', 1], ['class' => 'custom-class alert-link']);

        $this->Alert->linkFromPath('Title', 'Users::view', [1], ['class' => 'custom-class']);
    }
}
