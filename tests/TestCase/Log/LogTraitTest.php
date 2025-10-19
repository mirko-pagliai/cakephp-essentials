<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\Log;

use Cake\Essentials\Log\LogTrait;
use Cake\Http\ServerRequest;
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
    }

    /**
     * @link \Cake\Essentials\Log\LogTrait::log()
     */
    #[Test]
    public function testLogWithRequest(): void
    {
        $Instance = new class () {
            use LogTrait;

            protected ?ServerRequest $request {
                get => new ServerRequest(['environment' => ['REMOTE_ADDR' => '127.0.0.1']]);
            }
        };
        $Instance->log('Some log text...');

        $this->assertLogMessage(
            level: 'error',
            expectedMessage: '127.0.0.1 - Some log text...',
        );
    }

    /**
     * @link \Cake\Essentials\Log\LogTrait::log()
     */
    #[Test]
    public function testLogWithRequestButNoClientIp(): void
    {
        $Instance = new class () {
            use LogTrait;

            protected ?ServerRequest $request;
        };
        $Instance->log('Some log text...');

        $this->assertLogMessage(level: 'error', expectedMessage: 'Some log text...');
    }

    /**
     * @link \Cake\Essentials\Log\LogTrait::log()
     */
    #[Test]
    public function testLogWithNoRequest(): void
    {
        $Instance = new class () {
            use LogTrait;
        };
        $Instance->log('Some log text...');

        $this->assertLogMessage(level: 'error', expectedMessage: 'Some log text...');
    }
}
