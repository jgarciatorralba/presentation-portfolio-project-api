<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class EventBusMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return MessageBusInterface::class;
    }

    public function shouldDispatchEvents(DomainEvent ...$events): void
    {
        $callIndex = 0;

        $this->mock
            ->expects($this->exactly(count($events)))
            ->method('dispatch')
            ->with(
                $this->callback(
                    function (DomainEvent $event) use (&$callIndex, $events) {
                        return $event === $events[$callIndex++];
                    }
                )
            );
    }

    public function willThrowExceptions(Throwable ...$exceptions): void
    {
        $callIndex = 0;

        $this->mock
            ->expects($this->exactly(count($exceptions)))
            ->method('dispatch')
            ->willReturnCallback(
                function () use (&$callIndex, $exceptions) {
                    throw $exceptions[$callIndex++];
                }
            );
    }
}
