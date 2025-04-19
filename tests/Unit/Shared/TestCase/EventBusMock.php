<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Shared\Domain\Bus\Event\Event;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\TestCase;

final class EventBusMock extends AbstractMock
{
    private static int $callIndex;

    public function __construct(TestCase $testCase)
    {
        parent::__construct($testCase);
        self::$callIndex = 0;
    }

    protected function getClassName(): string
    {
        return EventBus::class;
    }

    public function shouldPublishEvents(Event ...$events): void
    {
        $this->mock
            ->expects($this->once())
            ->method('publish')
            ->with(
                $this->testCase->callback(
                    function (Event $event) use ($events): bool {
                        $expectedEvent = $events[self::$callIndex++] ?? null;

                        if (!$expectedEvent instanceof Event || $expectedEvent::class !== $event::class) {
                            return false;
                        }

                        if ($expectedEvent instanceof DomainEvent && $event instanceof DomainEvent) {
                            return $event->aggregateId() === $expectedEvent->aggregateId();
                        }

                        return false;
                    }
                )
            );
    }
}
