<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Bus\Query\InMemory;

use App\Shared\Domain\Bus\Query\Query;
use App\Shared\Domain\Bus\Query\Response;
use App\Shared\Infrastructure\Bus\Exception\QueryNotRegisteredException;
use App\Shared\Infrastructure\Bus\Query\InMemory\InMemorySymfonyQueryBus;
use App\Tests\Unit\Shared\Application\Testing\TestResponse;
use App\Tests\Unit\Shared\TestCase\QueryBusMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class InMemorySymfonyQueryBusTest extends TestCase
{
    private ?QueryBusMock $queryBusMock;
    private ?InMemorySymfonyQueryBus $sut;
    private ?MockObject $query;
    private ?Response $response;

    protected function setUp(): void
    {
        $this->queryBusMock = new QueryBusMock();
        $this->sut = new InMemorySymfonyQueryBus(
            queryBus: $this->queryBusMock->getMock()
        );
        $this->query = $this->createMock(Query::class);
        $this->response = new TestResponse(['foo' => 'bar']);
    }

    protected function tearDown(): void
    {
        $this->queryBusMock = null;
        $this->sut = null;
        $this->query = null;
        $this->response = null;
    }

    public function testItAsksQuerySuccessfully(): void
    {
        $stamp = new HandledStamp($this->response, 'handler');

        $this->queryBusMock
            ->shouldDispatchQuery($this->query, $stamp);

        $result = $this->sut->ask($this->query);
        $this->assertEquals($this->response->data(), $result->data());
    }

    public function testItThrowsQueryNotRegisteredException(): void
    {
        $queryClass = get_class($this->query);

        $this->queryBusMock
            ->willThrowException(new NoHandlerForMessageException());

        $this->expectException(QueryNotRegisteredException::class);
        $this->expectExceptionMessage("Query with class {$queryClass} has no handler registered");

        $this->sut->ask($this->query);
    }

    public function testItThrowsHandlerFailedException(): void
    {
        $previousException = new \Exception('Test exception message');
        $handlerFailedException = new HandlerFailedException(
            new Envelope($this->query),
            [$previousException]
        );

        $this->queryBusMock
            ->willThrowException($handlerFailedException);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($previousException->getMessage());

        $this->sut->ask($this->query);
    }
}
