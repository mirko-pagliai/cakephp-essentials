<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\Utility;

use Cake\Essentials\Utility\Text;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * TextTest.
 */
#[CoversClass(Text::class)]
class TextTest extends TestCase
{
    /**
     * @link \Cake\Essentials\Utility\Text::maskEmail()
     */
    #[Test]
    #[TestWith(['test', 't***'])]
    #[TestWith(['ab@cd.ef', 'a*@c*.e*'])]
    #[TestWith(['test@example.com', 't***@e******.c**'])]
    public function testMaskEmail(string $email, string $expected): void
    {
        $result = Text::maskEmail($email);
        $this->assertSame($expected, $result);
    }
}
