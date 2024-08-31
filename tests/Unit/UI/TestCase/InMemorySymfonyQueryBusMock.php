<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\TestCase;

use App\Shared\Domain\Bus\Query\Response;
use App\Shared\Infrastructure\Bus\Query\InMemory\InMemorySymfonyQueryBus;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class InMemorySymfonyQueryBusMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return InMemorySymfonyQueryBus::class;
    }

    public function willGetResult(Response $response): void
    {
        $this->mock
            ->expects($this->once())
            ->method('ask')
            ->willReturn($response);
    }

    public function willThrowException(\Throwable $exception): void
    {
        $this->mock
            ->expects($this->once())
            ->method('ask')
            ->willThrowException($exception);
    }
}
