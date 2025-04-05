<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\View\Helper;

use Cake\Essentials\View\Helper\DropdownHelper;
use Cake\Essentials\View\Helper\HtmlHelper;
use Cake\Essentials\View\View;
use Cake\TestSuite\TestCase;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * DropdownHelperTest
 */
#[CoversClass(DropdownHelper::class)]
#[UsesClass(HtmlHelper::class)]
class DropdownHelperTest extends TestCase
{
    /**
     * @var \Cake\Essentials\View\Helper\DropdownHelper
     */
    protected DropdownHelper $Dropdown;

    /**
     * @inheritDoc
     */
    #[Override]
    protected function setUp(): void
    {
        $this->Dropdown = new DropdownHelper(new View());
    }
}
