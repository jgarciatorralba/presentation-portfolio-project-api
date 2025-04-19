<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\TestCase;

use App\Shared\Domain\Bus\Query\Query;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Bus\Query\Response;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class QueryBusMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return QueryBus::class;
    }

    public function shouldAskQuery(Query $query, Response $response): void
    {
        $this->mock
            ->expects($this->once())
            ->method('ask')
            ->with($query)
            ->willReturn($response);
    }

    public function willThrowException(Query $query, \Throwable $exception): void
    {
        $this->mock
            ->expects($this->once())
            ->method('ask')
            ->with($query)
            ->willThrowException($exception);
    }
}
