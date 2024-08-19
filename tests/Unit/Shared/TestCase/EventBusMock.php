<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

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

    public function shouldDispatchEvents(DomainEvent ...$events): void
    {
        $this->mock
            ->expects($this->exactly(count($events)))
            ->method('dispatch')
            ->with(
                $this->callback(
                    function (DomainEvent $event) use ($events) {
                        return $event === $events[self::$callIndex++];
                    }
                )
            );
    }

    public function willThrowExceptions(Throwable ...$exceptions): void
    {
        $this->mock
            ->expects($this->exactly(count($exceptions)))
            ->method('dispatch')
            ->willReturnCallback(
                function () use ($exceptions) {
                    throw $exceptions[self::$callIndex++];
                }
            );
    }
}
