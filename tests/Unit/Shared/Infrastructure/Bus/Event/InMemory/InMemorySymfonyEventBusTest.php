<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Bus\Event\InMemory;

use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Shared\Infrastructure\Bus\Event\InMemory\InMemorySymfonyEventBus;
use App\Tests\Unit\Shared\TestCase\EventBusMock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;

final class InMemorySymfonyEventBusTest extends TestCase
{
    private ?EventBusMock $eventBusMock;
    private ?InMemorySymfonyEventBus $sut;

    protected function setUp(): void
    {
        $this->eventBusMock = new EventBusMock();
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
        $events = [
            [
                'event' => $this->createMock(DomainEvent::class),
                'exception' => null
            ],
            [
                'event' => $this->createMock(DomainEvent::class),
                'exception' => new NoHandlerForMessageException()
            ]
        ];

        $this->eventBusMock
            ->shouldDispatchEventsOrThrowExceptions($events);

        $result = $this->sut->publish(...array_column($events, 'event'));
        $this->assertNull($result);
    }
}
