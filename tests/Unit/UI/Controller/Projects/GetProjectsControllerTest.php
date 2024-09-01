<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Controller\Projects;

use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\Response;
use App\Tests\Unit\UI\TestCase\QueryBusMock;
use App\UI\Controller\Projects\GetProjectsController;
use App\UI\Request\Projects\GetProjectsRequest;
use App\UI\Validation\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class GetProjectsControllerTest extends TestCase
{
    private ?QueryBusMock $queryBusMock;
    private ?GetProjectsController $sut;
    private ?GetProjectsRequest $getProjectsRequest;

    protected function setUp(): void
    {
        $this->queryBusMock = new QueryBusMock();
        $this->sut = new GetProjectsController(
            queryBus: $this->queryBusMock->getMock(),
            commandBus: $this->createMock(CommandBus::class)
        );

        $requestStack = new RequestStack();
        $requestStack->push(new Request());
        $this->getProjectsRequest = new GetProjectsRequest(
            validator: $this->createMock(Validator::class),
            request: $requestStack
        );
    }

    protected function tearDown(): void
    {
        $this->queryBusMock = null;
        $this->sut = null;
        $this->getProjectsRequest = null;
    }

    public function testItReturnsResponse(): void
    {
        $this->queryBusMock
            ->shouldAskQuery($this->createMock(Response::class));

        $result = $this->sut->__invoke($this->getProjectsRequest);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($result->getStatusCode(), HttpResponse::HTTP_OK);
        $this->assertIsArray(json_decode($result->getContent(), true));
    }

    public function testItThrowsException(): void
    {
        $this->queryBusMock
            ->willThrowException($this->createMock(\Exception::class));

        $this->expectException(\Exception::class);

        $this->sut->__invoke($this->getProjectsRequest);
    }
}
