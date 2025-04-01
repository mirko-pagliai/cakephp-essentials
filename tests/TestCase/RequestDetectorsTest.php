<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase;

use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * RequestDetectorsTest.
 *
 * @see src/request_detectors.php
 */
class RequestDetectorsTest extends TestCase
{
    #[Test]
    #[TestWith([true, 'myAction'])]
    #[TestWith([false, 'notMyAction'])]
    #[TestWith([true, 'myAction', 'notMyAction'])]
    #[TestWith([false, 'notMyAction', 'againNotMyAction'])]
    public function testIsAction(bool $expectedIsAction, string ...$actions): void
    {
        $Request = new ServerRequest(['params' => ['action' => 'myAction']]);
        $this->assertSame($expectedIsAction, $Request->is('action', ...$actions));
    }

    public static function providerTestOtherActionDetectors(): Generator
    {
        foreach (['add', 'edit', 'delete', 'index'] as $action) {
            yield [true, $action, $action];
            yield [false, $action, 'view'];
        }

        yield [true, 'view', 'view'];
        yield [false, 'view', 'add'];
    }

    #[Test]
    #[DataProvider('providerTestOtherActionDetectors')]
    public function testOtherActionDetectors(bool $expectedIsDetector, string $currentAction, string $detectorAction): void
    {
        $Request = new ServerRequest(['params' => ['action' => $currentAction]]);
        $this->assertSame($expectedIsDetector, $Request->is($detectorAction));
    }

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
