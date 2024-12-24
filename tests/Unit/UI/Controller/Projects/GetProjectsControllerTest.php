<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Controller\Projects;

use App\Projects\Application\Query\GetProjects\GetProjectsQuery;
use App\Shared\Domain\Bus\Query\Response;
use App\Shared\Domain\Http\HttpStatusCode;
use App\Tests\Unit\UI\TestCase\QueryBusMock;
use App\Tests\Unit\UI\TestCase\ParameterBagMock;
use App\UI\Controller\Projects\GetProjectsController;
use App\UI\Request\Projects\GetProjectsRequest;
use App\UI\Validation\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class GetProjectsControllerTest extends TestCase
{
    private ?QueryBusMock $queryBusMock;
    private ?ParameterBagMock $parameterBagMock;
    private ?RequestStack $requestStack;
    private ?GetProjectsRequest $getProjectsRequest;

    protected function setUp(): void
    {
        $this->queryBusMock = new QueryBusMock($this);
        $this->parameterBagMock = new ParameterBagMock($this);

        $this->requestStack = new RequestStack();
        $this->requestStack->push(new Request());
        $this->getProjectsRequest = new GetProjectsRequest(
            validator: $this->createMock(Validator::class),
            request: $this->requestStack
        );
    }

    protected function tearDown(): void
    {
        $this->queryBusMock = null;
        $this->parameterBagMock = null;
        $this->requestStack = null;
        $this->getProjectsRequest = null;
    }

    public function testItReturnsResponse(): void
    {
        $requestContent = json_decode(
            $this->requestStack->getCurrentRequest()->getContent(),
            true
        );
        $baseUrl = 'http://localhost:8000';

        $this->parameterBagMock
            ->shouldGetBaseUrl($baseUrl)
            ->getMock();

        $sut = new GetProjectsController(
            queryBus: $this->queryBusMock->getMock(),
            params: $this->parameterBagMock->getMock()
        );

        $this->queryBusMock
            ->shouldAskQuery(
                new GetProjectsQuery(
                    pageSize: $requestContent['pageSize'] ?? null,
                    maxUpdatedAt: isset($requestContent['maxUpdatedAt'])
                        ? new \DateTimeImmutable($requestContent['maxUpdatedAt'])
                        : null
                ),
                $this->createMock(Response::class)
            );

        $result = $sut->__invoke($this->getProjectsRequest);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($result->getStatusCode(), HttpStatusCode::HTTP_OK->value);
        $this->assertIsArray(json_decode($result->getContent(), true));
    }

    public function testItThrowsException(): void
    {
        $requestContent = json_decode(
            $this->requestStack->getCurrentRequest()->getContent(),
            true
        );
        $baseUrl = 'http://localhost:8000';

        $this->parameterBagMock
            ->shouldGetBaseUrl($baseUrl)
            ->getMock();

        $sut = new GetProjectsController(
            queryBus: $this->queryBusMock->getMock(),
            params: $this->parameterBagMock->getMock()
        );

        $this->queryBusMock
            ->willThrowException(
                new GetProjectsQuery(
                    pageSize: $requestContent['pageSize'] ?? null,
                    maxUpdatedAt: isset($requestContent['maxUpdatedAt'])
                        ? new \DateTimeImmutable($requestContent['maxUpdatedAt'])
                        : null
                ),
                $this->createMock(\Exception::class)
            );

        $this->expectException(\Exception::class);

        $sut->__invoke($this->getProjectsRequest);
    }
}
