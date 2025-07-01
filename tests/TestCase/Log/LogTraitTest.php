<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\Log;

use Cake\Essentials\Log\LogTrait;
use Cake\Essentials\TestSuite\Traits\AssertLogTrait;
use Cake\Http\ServerRequest;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * LogTraitTest.
 *
 * This class extends `PHPUnit\Framework\TestCase`, to avoid conflicts.
 */
#[CoversTrait(LogTrait::class)]
class LogTraitTest extends TestCase
{
    use AssertLogTrait;

    public function tearDown(): void
    {
        parent::tearDown();

        unlink(LOGS . 'error.log');
    }

    #[Test]
    public function testLogWithRequest(): void
    {
        $Instance = new class () {
            use LogTrait;

            public ?ServerRequest $request = null;
        };
        $Instance->request = new ServerRequest();
        $Instance->request = $Instance->request->withEnv('REMOTE_ADDR', '127.0.0.1');
        $Instance->log('Some log text...');

        $this->assertLogContains('error: 127.0.0.1 - Some log text...', 'error.log');
    }

    #[Test]
    public function testLogWithRequestButNoClientIp(): void
    {
        $Instance = new class () {
            use LogTrait;

            public ?ServerRequest $request = null;
        };
        $Instance->log('Some log text...');

        $this->assertLogContains('error: Some log text...', 'error.log');
    }

    #[Test]
    public function testLogWithNoRequest(): void
    {
        $Instance = new class () {
            use LogTrait;
        };
        $Instance->log('Some log text...');

        $this->assertLogContains('error: Some log text...', 'error.log');
    }
}
