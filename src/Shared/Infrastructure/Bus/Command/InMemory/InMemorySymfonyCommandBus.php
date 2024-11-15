<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Command\InMemory;

use App\Shared\Application\Bus\Exception\CommandNotRegisteredException;
use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;

final readonly class InMemorySymfonyCommandBus implements CommandBus
{
    public function __construct(
        private MessageBusInterface $commandBus
    ) {
    }

    /**
     * @throws CommandNotRegisteredException
     * @throws ExceptionInterface
     */
    public function dispatch(Command $command): void
    {
        try {
            $this->commandBus->dispatch($command);
        } catch (NoHandlerForMessageException) {
            throw new CommandNotRegisteredException($command);
        } catch (HandlerFailedException $exception) {
            throw $exception->getPrevious() ?? $exception;
        }
    }
}
