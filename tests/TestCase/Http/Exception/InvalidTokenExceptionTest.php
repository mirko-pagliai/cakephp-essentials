<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\Http\Exception;

use Cake\Essentials\Http\Exception\InvalidTokenException;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

/**
 * InvalidTokenExceptionTest.
 */
#[CoversClass(InvalidTokenException::class)]
class InvalidTokenExceptionTest extends TestCase
{
    /**
     * @link \Cake\Essentials\Http\Exception\InvalidTokenException::__construct()
     */
    #[Test]
    public function testConstruct(): void
    {
        $this->expectExceptionCode(403);
        $this->expectExceptionMessage('Invalid token or request expired');
        throw new InvalidTokenException();
    }

    /**
     * @link \Cake\Essentials\Http\Exception\InvalidTokenException::__construct()
     */
    #[Test]
    public function testConstructWithCustomMessage(): void
    {
        $message = 'A custom message';
        $this->expectExceptionCode(403);
        $this->expectExceptionMessage($message);
        throw new InvalidTokenException($message);
    }
}
