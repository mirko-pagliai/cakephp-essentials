<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\Http\Exception;

use Cake\Essentials\Http\Exception\IpAddressNotAllowedException;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

/**
 * IpAddressNotAllowedExceptionTest.
 */
#[CoversClass(IpAddressNotAllowedException::class)]
class IpAddressNotAllowedExceptionTest extends TestCase
{
    /**
     * @link \Cake\Essentials\Http\Exception\IpAddressNotAllowedException::__construct()
     */
    #[Test]
    public function testConstruct(): void
    {
        $this->expectExceptionCode(403);
        $this->expectExceptionMessage('IP address not allowed for this operation');
        throw new IpAddressNotAllowedException();
    }

    /**
     * @link \Cake\Essentials\Http\Exception\IpAddressNotAllowedException::__construct()
     */
    #[Test]
    public function testConstructWithCustomMessage(): void
    {
        $message = 'A custom message';
        $this->expectExceptionCode(403);
        $this->expectExceptionMessage($message);
        throw new IpAddressNotAllowedException($message);
    }
}
