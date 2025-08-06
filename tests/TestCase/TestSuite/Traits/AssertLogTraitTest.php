<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\TestSuite\Traits;

use Cake\Essentials\TestSuite\Traits\AssertLogTrait;
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
        Log::error('Some log text...');
    }

    /**
     * @inheritDoc
     */
    public static function tearDownAfterClass(): void
    {
        unlink(LOGS . 'error.log');
    }

    /**
     * @link \Cake\Essentials\TestSuite\Traits\AssertLogTrait::assertLogContains()
     */
    #[Test]
    #[TestWith(['error.log'])]
    #[TestWith([LOGS . 'error.log'])]
    public function testAssertLogContains(string $logName): void
    {
        $TestCase = new class ('MyTest') extends TestCase {
            use AssertLogTrait;
        };
        $TestCase->assertLogContains('Some log text...', $logName);
    }

    /**
     * @link \Cake\Essentials\TestSuite\Traits\AssertLogTrait::assertLogContains()
     */
    #[Test]
    public function testAssertLogContainsOnFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        $TestCase = new class ('MyTest') extends TestCase {
            use AssertLogTrait;
        };
        $TestCase->assertLogContains('This text is not contained in the log file', LOGS . 'error.log');
    }

    /**
     * @link \Cake\Essentials\TestSuite\Traits\AssertLogTrait::assertLogContains()
     */
    #[Test]
    public function testAssertLogContainsOnNoExistingFile(): void
    {
        $this->expectException(AssertionFailedError::class);
        $TestCase = new class ('MyTest') extends TestCase {
            use AssertLogTrait;
        };
        $TestCase->assertLogContains('Some log text...', LOGS . 'no_existing_file.log');
    }
}
