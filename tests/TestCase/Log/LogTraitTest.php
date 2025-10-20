<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\Log;

use Cake\Controller\Controller;
use Cake\Essentials\Log\LogTrait;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\TestSuite\LogTestTrait;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * LogTraitTest.
 *
 * This class extends `\PHPUnit\Framework\TestCase` to avoid conflicts.
 */
#[CoversTrait(LogTrait::class)]
class LogTraitTest extends TestCase
{
    use LogTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setupLog('error');

        $Request = new ServerRequest(['environment' => ['REMOTE_ADDR' => '86.36.458.25']]);
        Router::setRequest($Request);
    }

    /**
     * @link \Cake\Essentials\Log\LogTrait::log()
     */
    #[Test]
    public function testLog(): void
    {
        $Instance = new class () {
            use LogTrait;
        };
        $Instance->log('Some log text...');

        $this->assertLogMessage(
            level: 'error',
            expectedMessage: '86.36.458.25 - Some log text...',
        );
    }

    /**
     * Tests for the ` log ()` method when the `getRequest()` method is available.
     *
     * @link \Cake\Essentials\Log\LogTrait::log()
     */
    #[Test]
    public function testLogWithGetRequestMethod(): void
    {
        $Request = new ServerRequest(['environment' => ['REMOTE_ADDR' => '56.54.25.63']]);
        $Instance = new class ($Request) extends Controller {
            use LogTrait;
        };
        $Instance->log('Some log text...');

        $this->assertLogMessage(
            level: 'error',
            expectedMessage: '56.54.25.63 - Some log text...',
        );
    }

    /**
     * Tests for the `log()` method when the `ServerRequest::clientIp()` returns `null`.
     *
     * @link \Cake\Essentials\Log\LogTrait::log()
     */
    #[Test]
    public function testLogWithNoClientIp(): void
    {
        Router::setRequest(new ServerRequest());

        $Instance = new class () {
            use LogTrait;
        };
        $Instance->log('Some log text...');

        $this->assertLogMessage(level: 'error', expectedMessage: 'Some log text...');
    }
}
