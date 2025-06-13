<?php

declare(strict_types=1);

namespace App\Tests\Feature\Trait;

use PHPUnit\Framework\ExpectationFailedException;

trait CanAccessLogs
{
    private const string LOGS_PATH = '/var/log/test/project.log';

    protected function clearLogs(): void
    {
        file_put_contents(
            dirname(__DIR__, 3) . self::LOGS_PATH,
            ''
        );
    }

    /**
     * @throws ExpectationFailedException
     * @throws \RuntimeException
     */
    protected function assertLogContains(string $message): void
    {
        $logs = file_get_contents(
            dirname(__DIR__, 3) . self::LOGS_PATH
        );

		if (false === $logs) {
			throw new \RuntimeException('Failed to read logs');
		}

        $this->assertStringContainsString(
            $message,
            $logs
        );
    }
}
