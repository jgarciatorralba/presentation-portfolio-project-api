<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\TestCase;

use App\Shared\Domain\Contract\Log\Logger;
use Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class LoggerMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return Logger::class;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function shouldLogInfo(
        string $message,
        array $context = [],
        int $times = 1
    ): void {
        $this->mock
            ->expects($this->exactly($times))
            ->method('info')
            ->with($message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function shouldLogError(
        string $message,
        array $context = [],
        int $times = 1
    ): void {
        $this->mock
            ->expects($this->exactly($times))
            ->method('error')
            ->with($message, $context);
    }
}
