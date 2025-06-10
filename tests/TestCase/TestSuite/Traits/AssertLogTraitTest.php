<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\TestSuite\Traits;

use Cake\Essentials\TestSuite\Traits\AssertLogTrait;
use Cake\Log\Engine\FileLog;
use Cake\Log\Log;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

/**
 * AssertLogTraitTest.
 *
 * This class extends `PHPUnit\Framework\TestCase`, to avoid conflicts.
 */
#[CoversTrait(AssertLogTrait::class)]
class AssertLogTraitTest extends TestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        Log::setConfig('error', [
            'engine' => FileLog::class,
            'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],
            'file' => 'error',
            'path' => LOGS,
        ]);

        Log::error('Some log text...');
    }

    /**
     * @inheritDoc
     */
    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        Log::drop('error');

        unlink(LOGS . 'error.log');
    }

    #[Test]
    #[TestWith(['error.log'])]
    #[TestWith([LOGS . 'error.log'])]
    public function testAssertLogContains(string $logName): void
    {
        $TestCase = new class ('Test') extends TestCase {
            use AssertLogTrait;
        };
        $TestCase->assertLogContains('Some log text...', $logName);
    }

    #[Test]
    public function testAssertLogContainsOnFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        $TestCase = new class ('Test') extends TestCase {
            use AssertLogTrait;
        };
        $TestCase->assertLogContains('This text is not contained in the log file', LOGS . 'error.log');
    }

    #[Test]
    public function testAssertLogContainsOnNoExistingFile(): void
    {
        $this->expectException(AssertionFailedError::class);
        $TestCase = new class ('Test') extends TestCase {
            use AssertLogTrait;
        };
        $TestCase->assertLogContains('Some log text...', LOGS . 'no_existing_file.log');
    }
}
