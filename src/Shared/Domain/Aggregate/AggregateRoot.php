<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Shared\Domain\Contract\ArraySerializable;

abstract class AggregateRoot implements ArraySerializable
{
    /** @var DomainEvent[] */
    private array $events = [];

    /** @return DomainEvent[] */
    final public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    final protected function recordEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    /** @return array<mixed> */
    abstract public function toArray(): array;
}
