<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\TestCase;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class CommandBusMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return CommandBus::class;
    }

    public function shouldDispatchCommand(): void
    {
        $this->mock
            ->expects($this->once())
            ->method('dispatch');
    }

    public function willThrowException(\Throwable $exception): void
    {
        $this->mock
            ->expects($this->once())
            ->method('dispatch')
            ->willThrowException($exception);
    }
}
