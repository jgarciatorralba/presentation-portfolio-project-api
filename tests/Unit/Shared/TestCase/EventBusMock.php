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

    public function shouldDispatchEvent(DomainEvent $event): void
    {
        $this->mock
            ->expects($this->once())
            ->method('dispatch')
            ->with($event);
    }

    public function willThrowException(Throwable $exception): void
    {
        $this->mock
            ->expects($this->once())
            ->method('dispatch')
            ->willThrowException($exception);
    }
}
