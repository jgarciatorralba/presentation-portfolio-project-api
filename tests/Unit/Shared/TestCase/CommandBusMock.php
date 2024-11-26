<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Shared\Domain\Bus\Command\Command;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;
use Symfony\Component\Messenger\MessageBusInterface;

final class CommandBusMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return MessageBusInterface::class;
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
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     */
    public function willThrowException(\Throwable $exception): void
    {
        $this->mock
            ->expects($this->once())
            ->method('dispatch')
            ->willThrowException($exception);
    }
}
