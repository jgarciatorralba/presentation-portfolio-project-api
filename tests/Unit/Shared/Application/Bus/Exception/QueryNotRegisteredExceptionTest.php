<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Application\Bus\Exception;

use App\Shared\Application\Bus\Exception\QueryNotRegisteredException;
use App\Shared\Domain\Bus\Query\Query;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

final class QueryNotRegisteredExceptionTest extends TestCase
{
    private Stub&Query $query;

    protected function setUp(): void
    {
        $this->query = $this->createStub(Query::class);
    }

    public function testItIsCreated(): void
    {
        $exception = new QueryNotRegisteredException($this->query);
        $queryClass = $this->query instanceof Stub
            ? $this->query::class
            : self::class;

        $this->assertEquals(
            "Query with class {$queryClass} has no handler registered",
            $exception->getMessage()
        );
    }
}
