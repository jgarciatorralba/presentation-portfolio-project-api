<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Shared\Domain\Bus\Query\Query;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class QueryBusMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return MessageBusInterface::class;
    }

    public function shouldDispatchQuery(Query $query, HandledStamp $stamp): void
    {
        $envelope = new Envelope($query, [$stamp]);

        $this->mock
            ->expects($this->once())
            ->method('dispatch')
            ->with($query)
            ->willReturn($envelope);
    }

    public function willThrowException(\Throwable $exception): void
    {
        $this->mock
            ->expects($this->once())
            ->method('dispatch')
            ->willThrowException($exception);
    }
}
