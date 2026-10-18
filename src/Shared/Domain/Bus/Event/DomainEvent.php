<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Event;

abstract readonly class DomainEvent extends Event
{
    public function __construct(
        private string $aggregateId,
        ?string $eventId = null,
        ?string $occurredOn = null
    ) {
        parent::__construct($eventId, $occurredOn);
    }

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    /**
     * @return array<string, string|array<string, string>>
     */
    #[\Override]
    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            [
                'attributes' => array_merge(
                    $this->toPrimitives(),
                    [
                        'aggregateId' => $this->aggregateId
                    ]
                )
            ]
        );
    }
}
