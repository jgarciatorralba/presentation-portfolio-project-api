<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\TestCase;

use App\Shared\Domain\Bus\Event\Event;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class EventBusMock extends AbstractMock
{
    private static int $callIndex;

    public function __construct()
    {
        parent::__construct();
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
                $this->callback(
                    function (Event $event) use ($eventTypes) {
                        return $event::eventType() === $eventTypes[self::$callIndex++];
                    }
                )
            );
    }
}
