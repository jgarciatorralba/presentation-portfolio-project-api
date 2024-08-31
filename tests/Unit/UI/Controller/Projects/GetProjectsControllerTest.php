<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Controller\Projects;

use App\Tests\Unit\UI\TestCase\InMemorySymfonyCommandBusMock;
use App\Tests\Unit\UI\TestCase\InMemorySymfonyQueryBusMock;
use App\Tests\Unit\UI\TestCase\ValidatorMock;
use App\Shared\Domain\Bus\Query\Response;
use App\UI\Controller\Projects\GetProjectsController;
use App\UI\Request\Projects\GetProjectsRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class GetProjectsControllerTest extends TestCase
{
    private ?InMemorySymfonyQueryBusMock $queryBusMock;
    private ?InMemorySymfonyCommandBusMock $commandBusMock;
    private ?ValidatorMock $validatorMock;
    private ?RequestStack $requestStack;
    private ?GetProjectsController $sut;

    protected function setUp(): void
    {
        $this->queryBusMock = new InMemorySymfonyQueryBusMock();
        $this->commandBusMock = new InMemorySymfonyCommandBusMock();
        $this->validatorMock = new ValidatorMock();
        $this->requestStack = new RequestStack();
        $this->sut = new GetProjectsController(
            queryBus: $this->queryBusMock->getMock(),
            commandBus: $this->commandBusMock->getMock()
        );
    }

    protected function tearDown(): void
    {
        $this->queryBusMock = null;
        $this->commandBusMock = null;
        $this->validatorMock = null;
        $this->requestStack = null;
        $this->sut = null;
    }

    public function testItReturnsResponse(): void
    {
        $this->requestStack->push(new Request());

        $getProjectsRequest = new GetProjectsRequest(
            validator: $this->validatorMock->getMock(),
            request: $this->requestStack
        );

        $this->queryBusMock
            ->willGetResult($this->createMock(Response::class));

        $result = $this->sut->__invoke($getProjectsRequest);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($result->getStatusCode(), HttpResponse::HTTP_OK);
        $this->assertIsArray(json_decode($result->getContent(), true));
    }

    public function testItThrowsException(): void
    {
        $this->requestStack->push(new Request());

        $getProjectsRequest = new GetProjectsRequest(
            validator: $this->validatorMock->getMock(),
            request: $this->requestStack
        );

        $this->queryBusMock
            ->willThrowException($this->createMock(\Exception::class));

        $this->expectException(\Exception::class);

        $this->sut->__invoke($getProjectsRequest);
    }
}
