<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Shared\Domain\Contract\Logger;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;

final class LoggerMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return Logger::class;
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     *
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
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     *
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
