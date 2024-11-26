<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\TestCase;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;

final class CommandBusMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return CommandBus::class;
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     */
    public function shouldDispatchCommand(Command $command): void
    {
        $this->mock
            ->expects($this->once())
            ->method('dispatch')
            ->with($command);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     */
    public function willThrowException(Command $command, \Throwable $exception): void
    {
        $this->mock
            ->expects($this->once())
            ->method('dispatch')
            ->with($command)
            ->willThrowException($exception);
    }
}
