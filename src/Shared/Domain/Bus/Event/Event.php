<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Event;

use App\Shared\Domain\Contract\Mappable;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Utils;

abstract readonly class Event implements Mappable
{
    protected string $eventId;
    protected string $occurredOn;

    /** @throws \InvalidArgumentException */
    public function __construct(
        ?string $eventId = null,
        ?string $occurredOn = null
    ) {
        $this->eventId = $eventId ?: Uuid::random()->value();
        $this->occurredOn = $occurredOn ?: Utils::dateToUTCString(new \DateTimeImmutable());
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function occurredOn(): string
    {
        return $this->occurredOn;
    }

    /**
     * @return array<string, string|array<string, string>>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->eventId,
            'eventType' => static::eventType(),
            'occurredOn' => $this->occurredOn,
            'attributes' => $this->toPrimitives()
        ];
    }

    /**
     * @return array{}
     */
    protected function toPrimitives(): array
    {
        return [];
    }

    abstract public static function eventType(): string;
}
