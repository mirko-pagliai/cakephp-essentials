<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase;

use Cake\Essentials\View\Helper\FormHelper;
use Cake\Essentials\View\Helper\HtmlHelper;
use Cake\Essentials\View\View;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

/**
 * ViewTest.
 */
#[CoversClass(View::class)]
class ViewTest extends TestCase
{
    #[CoversMethod(View::class, 'initialize')]
    #[Test]
    public function testInitialize(): void
    {
        $View = new View();
        $View->initialize();

        // `HtmlHelper`
        $expectedIconDefaultsConfig = [
            'tag' => 'i',
            'namespace' => 'fas',
            'prefix' => 'fa',
            'size' => null,
        ];
        $HtmlHelper = $View->helpers()->get('Html');
        $this->assertInstanceOf(HtmlHelper::class, $HtmlHelper);
        $this->assertSame($expectedIconDefaultsConfig, $HtmlHelper->getConfig('iconDefaults'));

        // `FormHelper`
        $FormHelper = $View->helpers()->get('Form');
        $this->assertInstanceOf(FormHelper::class, $FormHelper);
    }
}
