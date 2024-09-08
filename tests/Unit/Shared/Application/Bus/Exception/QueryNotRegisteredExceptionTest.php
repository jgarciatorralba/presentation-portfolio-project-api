<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Application\Bus\Exception;

use App\Shared\Application\Bus\Exception\QueryNotRegisteredException;
use App\Shared\Domain\Bus\Query\Query;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class QueryNotRegisteredExceptionTest extends TestCase
{
    private ?MockObject $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(Query::class);
    }

    protected function tearDown(): void
    {
        $this->query = null;
    }

    public function testExceptionIsCreated(): void
    {
        $exception = new QueryNotRegisteredException($this->query);
        $queryClass = get_class($this->query);

        $this->assertEquals(
            "Query with class {$queryClass} has no handler registered",
            $exception->getMessage()
        );
    }
}
