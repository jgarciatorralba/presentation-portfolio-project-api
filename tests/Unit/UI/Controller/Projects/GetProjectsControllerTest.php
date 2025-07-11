<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Controller\Projects;

use App\Projects\Application\Query\GetProjects\GetProjectsQuery;
use App\Shared\Domain\Bus\Query\Response;
use App\Shared\Domain\Http\HttpStatusCode;
use App\Tests\Unit\UI\TestCase\QueryBusMock;
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
    private ?RequestStack $requestStack;
    private ?GetProjectsRequest $getProjectsRequest;

    protected function setUp(): void
    {
        $this->queryBusMock = new QueryBusMock($this);

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
        $this->requestStack = null;
        $this->getProjectsRequest = null;
    }

    public function testItReturnsResponse(): void
    {
        $requestContent = json_decode(
            $this->requestStack->getCurrentRequest()->getContent(),
            true
        );

        $sut = new GetProjectsController(
            queryBus: $this->queryBusMock->getMock()
        );

        $this->queryBusMock
            ->shouldAskQuery(
                new GetProjectsQuery(
                    pageSize: $requestContent['pageSize'] ?? null,
                    maxPushedAt: isset($requestContent['maxPushedAt'])
                        ? new \DateTimeImmutable($requestContent['maxPushedAt'])
                        : null
                ),
                $this->createMock(Response::class)
            );

        $result = $sut->__invoke($this->getProjectsRequest);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($result->getStatusCode(), HttpStatusCode::HTTP_OK->value);
        $this->assertIsArray(json_decode($result->getContent(), true));

        $this->assertEquals($result->headers->get('Content-Type'), 'application/json');
        $this->assertEquals($result->headers->get('Next'), '');
    }

    public function testItThrowsException(): void
    {
        $requestContent = json_decode(
            $this->requestStack->getCurrentRequest()->getContent(),
            true
        );

        $sut = new GetProjectsController(
            queryBus: $this->queryBusMock->getMock()
        );

        $this->queryBusMock
            ->willThrowException(
                new GetProjectsQuery(
                    pageSize: $requestContent['pageSize'] ?? null,
                    maxPushedAt: isset($requestContent['maxPushedAt'])
                        ? new \DateTimeImmutable($requestContent['maxPushedAt'])
                        : null
                ),
                $this->createMock(\Exception::class)
            );

        $this->expectException(\Exception::class);

        $sut->__invoke($this->getProjectsRequest);
    }
}
