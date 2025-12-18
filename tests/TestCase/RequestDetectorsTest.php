<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase;

use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

require_once ROOT . 'src/request_detectors.php';

/**
 * RequestDetectorsTest.
 *
 * @link src/request_detectors.php
 */
class RequestDetectorsTest extends TestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        Configure::write('trustedIpAddress', ['45.46.47.48', '192.168.0.100']);
    }

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
        $Request = new ServerRequest(['environment' => ['REMOTE_ADDR' => $clientIp]]);
        $result = $Request->is('localhost');

        $this->assertSame($expectedIsLocalhost, $result);
    }

    #[Test]
    #[TestWith([false, '99.99.99.98'])]
    #[TestWith([true, '99.99.99.99'])]
    #[TestWith([true, '99.99.99.98', '99.99.99.99'])]
    #[TestWith([false, '99.99.99.97', '99.99.99.98'])]
    public function testIsIp(bool $expectedIsIp, string ...$ip): void
    {
        $Request = new ServerRequest(['environment' => ['REMOTE_ADDR' => '99.99.99.99']]);
        $result = $Request->is('ip', ...$ip);

        $this->assertSame($expectedIsIp, $result);
    }

    #[Test]
    #[TestWith([true, '127.0.0.1'])]
    #[TestWith([true, '::1'])]
    #[TestWith([false, '127.0.1.1'])]
    #[TestWith([false, '192.168.0.99'])]
    #[TestWith([true, '192.168.0.100'])]
    #[TestWith([false, '192.168.1.100'])]
    #[TestWith([true, '45.46.47.48'])]
    #[TestWith([false, '45.46.47.49'])]
    public function testIsTrustedClient(bool $expectedIsTrustedClient, string $ipAddress): void
    {
        $Request = new ServerRequest(['environment' => ['REMOTE_ADDR' => $ipAddress]]);
        $result = $Request->is('trustedClient');

        $this->assertSame($expectedIsTrustedClient, $result);
    }
}
