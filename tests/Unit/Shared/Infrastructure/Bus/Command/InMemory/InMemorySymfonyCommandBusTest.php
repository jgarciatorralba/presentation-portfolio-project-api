<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Bus\Command\InMemory;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Infrastructure\Bus\Command\InMemory\InMemorySymfonyCommandBus;
use App\Shared\Infrastructure\Bus\Exception\CommandNotRegisteredException;
use App\Tests\Unit\Shared\TestCase\CommandBusMock;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;

final class InMemorySymfonyCommandBusTest extends TestCase
{
    private ?CommandBusMock $commandBusMock;
    private ?InMemorySymfonyCommandBus $commandBus;
    private ?MockObject $command;

    protected function setUp(): void
    {
        $this->commandBusMock = new CommandBusMock();
        $this->commandBus = new InMemorySymfonyCommandBus(
            commandBus: $this->commandBusMock->getMock()
        );
        $this->command = $this->createMock(Command::class);
    }

    protected function tearDown(): void
    {
        $this->commandBusMock = null;
        $this->commandBus = null;
        $this->command = null;
    }

    public function testItDispatchesCommandSuccessfully(): void
    {
        $this->commandBusMock
            ->shouldDispatchCommand($this->command);

        $result = $this->commandBus->dispatch($this->command);
        $this->assertNull($result);
    }

    public function testItThrowsCommandNotRegisteredException(): void
    {
        $commandClass = get_class($this->command);

        $this->commandBusMock
            ->willThrowException(new NoHandlerForMessageException());

        $this->expectException(CommandNotRegisteredException::class);
        $this->expectExceptionMessage("Command with class {$commandClass} has no handler registered");

        $this->commandBus->dispatch($this->command);
    }

    public function testItThrowsHandlerFailedException(): void
    {
        $exceptionMessage = 'Test exception message';
        $previousException = new Exception($exceptionMessage);
        $handlerFailedException = new HandlerFailedException(
            new Envelope($this->command),
            [$previousException]
        );

        $this->commandBusMock
            ->willThrowException($handlerFailedException);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($exceptionMessage);

        $this->commandBus->dispatch($this->command);
    }
}
