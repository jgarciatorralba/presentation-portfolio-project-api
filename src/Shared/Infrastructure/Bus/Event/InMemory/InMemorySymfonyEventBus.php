<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event\InMemory;

use App\Shared\Domain\Bus\Event\Event;
use App\Shared\Domain\Bus\Event\EventBus;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class InMemorySymfonyEventBus implements EventBus
{
    public function __construct(
        private MessageBusInterface $eventBus
    ) {
    }

    /** @throws ExceptionInterface */
    #[\Override]
    public function publish(Event ...$events): void
    {
        foreach ($events as $event) {
            try {
                $this->eventBus->dispatch($event);
            } catch (NoHandlerForMessageException) {
            }
        }
    }
}
