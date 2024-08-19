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
    /** @var MockObject[]|null $events */
    private ?array $events;

    protected function setUp(): void
    {
        $this->eventBusMock = new EventBusMock();
        $this->sut = new InMemorySymfonyEventBus(
            eventBus: $this->eventBusMock->getMock()
        );
        $this->events[] = $this->createMock(DomainEvent::class);
    }

    protected function tearDown(): void
    {
        $this->eventBusMock = null;
        $this->sut = null;
        $this->events = null;
    }

    public function testItPublishesEventsSuccessfully(): void
    {
        foreach ($this->events as $event) {
            $this->eventBusMock
                ->shouldDispatchEvent($event);
        }

        $result = $this->sut->publish(...$this->events);
        $this->assertNull($result);
    }

    public function testItCatchesNoHandlerForMessageException(): void
    {
        foreach ($this->events as $event) {
            $this->eventBusMock
                ->willThrowException(new NoHandlerForMessageException());
        }

        $result = $this->sut->publish(...$this->events);
        $this->assertNull($result);
    }
}
