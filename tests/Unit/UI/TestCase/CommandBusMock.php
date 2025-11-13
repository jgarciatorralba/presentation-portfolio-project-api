<?php

declare(strict_types=1);

namespace Tests\Unit\UI\TestCase;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Domain\Bus\Command\CommandBus;
use Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class CommandBusMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return CommandBus::class;
    }

    public function shouldDispatchCommand(Command $command): void
    {
        $this->mock
            ->expects($this->once())
            ->method('dispatch')
            ->with($command);
    }

    public function willThrowException(Command $command, \Throwable $exception): void
    {
        $this->mock
            ->expects($this->once())
            ->method('dispatch')
            ->with($command)
            ->willThrowException($exception);
    }
}
