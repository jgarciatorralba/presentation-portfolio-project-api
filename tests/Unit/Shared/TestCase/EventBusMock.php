<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use Symfony\Component\Messenger\MessageBusInterface;

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
        return MessageBusInterface::class;
    }

    /**
     * @param list<array{
     *      event: DomainEvent,
     *      exception: \Throwable|null
     * }> $events
     */
    public function shouldDispatchEventsOrThrowExceptions(array $events): void
    {
        $this->mock
            ->expects($this->exactly(count($events)))
            ->method('dispatch')
            ->with(
                $this->callback(
                    function (DomainEvent $event) use ($events) {
                        if ($events[self::$callIndex]['exception'] !== null) {
                            throw $events[self::$callIndex++]['exception'];
                        }
                        return $event === $events[self::$callIndex++]['event'];
                    }
                )
            );
    }
}
