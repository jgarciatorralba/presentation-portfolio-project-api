<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Shared\Domain\Bus\Command\Command;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use Symfony\Component\Messenger\MessageBusInterface;

final class CommandBusMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return MessageBusInterface::class;
    }

    public function shouldDispatchCommand(Command $command): void
    {
        $this->mock
            ->expects($this->once())
            ->method('dispatch')
            ->with($command);
    }

    public function willThrowException(\Throwable $exception): void
    {
        $this->mock
            ->expects($this->once())
            ->method('dispatch')
            ->willThrowException($exception);
    }
}
