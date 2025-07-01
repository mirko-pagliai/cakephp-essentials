<?php
declare(strict_types=1);

namespace Cake\Essentials\TestSuite\Traits;

use Symfony\Component\Filesystem\Path;

/**
 * Provides utility methods to assert the content of log files during testing.
 */
trait AssertLogTrait
{
    /**
     * Asserts that the specified log file contains the expected content.
     *
     * @param string $expectedContent The content expected to be found in the log file.
     * @param string $filename The name or path of the log file to check. If the path is relative, it is resolved relative to the LOGS directory.
     * @param string $message Optional assertion failure message.
     * @return void
     */
    public function assertLogContains(string $expectedContent, string $filename, string $message = ''): void
    {
        if (Path::isRelative($filename)) {
            $filename = rtrim(LOGS, DS) . DS . $filename;
        }

        $this->assertFileIsReadable($filename);
        $this->assertStringContainsString($expectedContent, file_get_contents($filename) ?: '', $message);
    }
}
