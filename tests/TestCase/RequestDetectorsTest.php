<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase;

use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * RequestDetectorsTest.
 *
 * @see src/request_detectors.php
 */
class RequestDetectorsTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    #[TestWith([true, '127.0.0.1'])]
    #[TestWith([true, '::1'])]
    #[TestWith([false, '99.99.99.99'])]
    #[TestWith([false, '127.0.1.1'])]
    public function testIsLocalhost(bool $expectedIsLocalhost, string $clientIp): void
    {
        $Request = $this->createPartialMock(ServerRequest::class, ['clientIp']);
        $Request
            ->expects($this->once())
            ->method('clientIp')
            ->willReturn($clientIp);

        $this->assertSame($expectedIsLocalhost, $Request->is('localhost'));
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    #[TestWith([false, '99.99.99.98'])]
    #[TestWith([true, '99.99.99.99'])]
    #[TestWith([true, '99.99.99.98', '99.99.99.99'])]
    #[TestWith([false, '99.99.99.97', '99.99.99.98'])]
    public function testIsIp(bool $expectedIsIp, string ...$ip): void
    {
        $Request = $this->createPartialMock(ServerRequest::class, ['clientIp']);
        $Request
            ->expects($this->once())
            ->method('clientIp')
            ->willReturn('99.99.99.99');

        $this->assertSame($expectedIsIp, $Request->is('ip', ...$ip));
    }
}
