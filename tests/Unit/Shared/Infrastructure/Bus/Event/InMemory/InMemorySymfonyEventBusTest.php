<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Infrastructure\Bus\Event\InMemory;

use App\Shared\Domain\Bus\Event\Event;
use App\Shared\Infrastructure\Bus\Event\InMemory\InMemorySymfonyEventBus;
use Tests\Unit\Shared\Infrastructure\Testing\SymfonyMessageBusMock as EventBusMock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;

final class InMemorySymfonyEventBusTest extends TestCase
{
    private ?EventBusMock $eventBusMock;
    private ?InMemorySymfonyEventBus $sut;

    protected function setUp(): void
    {
        $this->eventBusMock = new EventBusMock($this);
        $this->sut = new InMemorySymfonyEventBus(
            eventBus: $this->eventBusMock->getMock()
        );
    }

    protected function tearDown(): void
    {
        $this->eventBusMock = null;
        $this->sut = null;
    }

    public function testItPublishesEventsOrCatchesExceptions(): void
    {
        $firstMockedEvent = $this->createStub(Event::class);
        $firstMockedEvent->method('eventId')->willReturn('first-event-id');

        $secondMockedEvent = $this->createStub(Event::class);
        $secondMockedEvent->method('eventId')->willReturn('second-event-id');

        $events = [
            [
                'event' => $firstMockedEvent,
                'exception' => null
            ],
            [
                'event' => $secondMockedEvent,
                'exception' => new NoHandlerForMessageException()
            ]
        ];

        $this->eventBusMock
            ->shouldDispatchEventsOrThrowExceptions($events);

        $result = $this->sut->publish(...array_column($events, 'event'));
        $this->assertNull($result);
    }
}
