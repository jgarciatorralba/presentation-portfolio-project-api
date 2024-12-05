<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Shared\Domain\Bus\Event\Event;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;

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

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     */
    public function shouldPublishEvents(Event ...$events): void
    {
        $this->mock
            ->expects($this->exactly(count($events)))
            ->method('publish')
            ->with(
                $this->callback(
                    function (Event $event) use ($events): bool {
                        $expectedEvent = $events[self::$callIndex++];

                        if (get_class($event) !== get_class($expectedEvent)) {
                            return false;
                        }

                        if ($expectedEvent instanceof DomainEvent && $event instanceof DomainEvent) {
                            return $event->aggregateId() === $expectedEvent->aggregateId();
                        }

                        return true;
                    }
                )
            );
    }
}
