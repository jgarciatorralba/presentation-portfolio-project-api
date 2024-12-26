<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Shared\Domain\Bus\Event\Event;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;

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
            ->expects($this->once())
            ->method('publish')
            ->with(
                $this->testCase->callback(
                    function (Event $event) use ($events): bool {
                        $expectedEvent = $events[self::$callIndex++] ?? null;

                        if ($expectedEvent === null || $expectedEvent::class !== $event::class) {
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
