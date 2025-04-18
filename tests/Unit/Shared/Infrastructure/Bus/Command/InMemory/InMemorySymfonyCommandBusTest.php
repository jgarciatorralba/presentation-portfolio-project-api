<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Bus\Command\InMemory;

use App\Shared\Application\Bus\Exception\CommandNotRegisteredException;
use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Infrastructure\Bus\Command\InMemory\InMemorySymfonyCommandBus;
use App\Tests\Unit\Shared\Infrastructure\Testing\SymfonyMessageBusMock as CommandBusMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;

final class InMemorySymfonyCommandBusTest extends TestCase
{
    private ?CommandBusMock $commandBusMock;
    private ?InMemorySymfonyCommandBus $sut;
    private ?MockObject $command;

    protected function setUp(): void
    {
        $this->commandBusMock = new CommandBusMock($this);
        $this->sut = new InMemorySymfonyCommandBus(
            commandBus: $this->commandBusMock->getMock()
        );
        $this->command = $this->createMock(Command::class);
    }

    protected function tearDown(): void
    {
        $this->commandBusMock = null;
        $this->sut = null;
        $this->command = null;
    }

    public function testItDispatchesCommandSuccessfully(): void
    {
        $this->commandBusMock
            ->shouldDispatchCommand($this->command);

        $result = $this->sut->dispatch($this->command);
        $this->assertNull($result);
    }

    public function testItThrowsCommandNotRegisteredException(): void
    {
        $commandClass = $this->command instanceof MockObject
            ? $this->command::class
            : self::class;

        $this->commandBusMock
            ->willThrowException(new NoHandlerForMessageException());

        $this->expectException(CommandNotRegisteredException::class);
        $this->expectExceptionMessage("Command with class {$commandClass} has no handler registered");

        $this->sut->dispatch($this->command);
    }

    public function testItThrowsHandlerFailedException(): void
    {
        $previousException = new \Exception('Test exception message');
        $handlerFailedException = new HandlerFailedException(
            new Envelope($this->command),
            [$previousException]
        );

        $this->commandBusMock
            ->willThrowException($handlerFailedException);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($previousException->getMessage());

        $this->sut->dispatch($this->command);
    }
}
