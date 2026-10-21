<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event\InProcess;

use App\Shared\Domain\Bus\Event\Event;
use App\Shared\Domain\Bus\Event\EventBus;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class InProcessSymfonyEventBus implements EventBus
{
    public function __construct(
		#[Target('event.bus')]
        private MessageBusInterface $bus
    ) {
    }

    /** @throws ExceptionInterface */
    #[\Override]
    public function publish(Event ...$events): void
    {
        foreach ($events as $event) {
            try {
                $this->bus->dispatch($event);
            } catch (NoHandlerForMessageException) {
            }
        }
    }
}
