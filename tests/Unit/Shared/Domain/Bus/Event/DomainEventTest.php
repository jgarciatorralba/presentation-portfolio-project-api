<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\Bus\Event;

use PHPUnit\Framework\TestCase;
use App\Shared\Domain\Bus\Event\DomainEvent;

final class DomainEventTest extends TestCase
{
    public function testItIsMappable(): void
    {
        $testAggregateId = 'abc123';

        $event = new class ($testAggregateId) extends DomainEvent {
            public function __construct(
                string $aggregateId,
            ) {
                parent::__construct($aggregateId);
            }

            public static function eventType(): string
            {
                return 'test.event';
            }
        };

        $this->assertEquals([
            'id' => $event->eventId(),
            'eventType' => 'test.event',
            'occurredOn' => $event->occurredOn(),
            'attributes' => [
                'aggregateId' => $testAggregateId,
            ],
        ], $event->toArray());
    }
}
