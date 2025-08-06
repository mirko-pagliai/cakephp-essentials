<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase;

use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use Generator;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * RequestDetectorsTest.
 *
 * @link src/request_detectors.php
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
        $allActions = ['add', 'edit', 'delete', 'index', 'view'];

        foreach ($allActions as $action) {
            yield [true, $action, $action];

            // `$actionsToTest` will now contain all other actions
            $actionsToTest = $allActions;
            $key = array_search(needle: $action, haystack: $actionsToTest);
            unset($actionsToTest[$key]);

            foreach ($actionsToTest as $actionToTest) {
                yield [false, $action, $actionToTest];
            }
        }
    }

    #[Test]
    #[DataProvider('providerTestOtherActionDetectors')]
    public function testOtherActionDetectors(bool $expectedIsDetector, string $detectorAction, string $currentAction): void
    {
        $Request = new ServerRequest(['params' => ['action' => $currentAction]]);
        $this->assertSame($expectedIsDetector, $Request->is($detectorAction));
    }

    #[Test]
    #[TestWith([true, '127.0.0.1'])]
    #[TestWith([true, '::1'])]
    #[TestWith([false, '99.99.99.99'])]
    #[TestWith([false, '127.0.1.1'])]
    public function testIsLocalhost(bool $expectedIsLocalhost, string $clientIp): void
    {
        /** @var \Mockery\MockInterface&\Cake\Http\ServerRequest $Request */
        $Request = Mockery::mock(ServerRequest::class . '[clientIp]');
        $Request->shouldReceive('clientIp')->andReturn($clientIp);

        $this->assertSame($expectedIsLocalhost, $Request->is('localhost'));
    }

    #[Test]
    #[TestWith([false, '99.99.99.98'])]
    #[TestWith([true, '99.99.99.99'])]
    #[TestWith([true, '99.99.99.98', '99.99.99.99'])]
    #[TestWith([false, '99.99.99.97', '99.99.99.98'])]
    public function testIsIp(bool $expectedIsIp, string ...$ip): void
    {
        /** @var \Mockery\MockInterface&\Cake\Http\ServerRequest $Request */
        $Request = Mockery::mock(ServerRequest::class . '[clientIp]');
        $Request->shouldReceive('clientIp')->andReturn('99.99.99.99');

        $this->assertSame($expectedIsIp, $Request->is('ip', ...$ip));
    }
}
