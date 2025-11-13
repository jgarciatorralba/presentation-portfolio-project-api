<?php

declare(strict_types=1);

namespace Tests\Unit\UI\TestCase;

use App\Shared\Domain\Bus\Event\Event;
use App\Shared\Domain\Bus\Event\EventBus;
use Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
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

    public function shouldPublishEvents(string ...$eventTypes): void
    {
        $this->mock
            ->expects($this->exactly(count($eventTypes)))
            ->method('publish')
            ->with(
                $this->testCase->callback(
                    fn (Event $event): bool => $event::eventType() === $eventTypes[self::$callIndex++]
                )
            );
    }
}
