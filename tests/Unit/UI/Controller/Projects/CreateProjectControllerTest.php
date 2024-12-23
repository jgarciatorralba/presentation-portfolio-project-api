<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Controller\Projects;

use App\Projects\Application\Command\CreateProject\CreateProjectCommand;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Service\LocalDateTimeZoneConverter;
use App\Shared\Domain\Http\HttpStatusCode;
use App\Tests\Unit\UI\TestCase\CommandBusMock;
use App\Tests\Unit\UI\TestCase\ParameterBagMock;
use App\UI\Controller\Projects\CreateProjectController;
use App\UI\Request\Projects\CreateProjectRequest;
use App\UI\Validation\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class CreateProjectControllerTest extends TestCase
{
    private ?CommandBusMock $commandBusMock;
    private ?ParameterBagMock $parameterBagMock;
    private ?RequestStack $requestStack;
    private ?CreateProjectRequest $createProjectRequest;

    protected function setUp(): void
    {
        $this->commandBusMock = new CommandBusMock($this);
        $this->parameterBagMock = new ParameterBagMock($this);

        $this->requestStack = new RequestStack();
        $this->requestStack->push(new Request(
            content: json_encode([
                'id' => 1,
                'name' => 'Project Name',
                'repository' => 'https://github.com/foo/bar',
                'archived' => false,
                'lastPushedAt' => '2021-10-10T10:00:00+00:00'
            ])
        ));
        $this->createProjectRequest = new CreateProjectRequest(
            validator: $this->createMock(Validator::class),
            request: $this->requestStack,
            dateTimeConverter: $this->createMock(LocalDateTimeZoneConverter::class)
        );
    }

    protected function tearDown(): void
    {
        $this->commandBusMock = null;
        $this->parameterBagMock = null;
        $this->requestStack = null;
        $this->createProjectRequest = null;
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

        $sut = new CreateProjectController(
            commandBus: $this->commandBusMock->getMock(),
            queryBus: $this->createMock(QueryBus::class),
            params: $this->parameterBagMock->getMock()
        );

        $this->commandBusMock
            ->shouldDispatchCommand(
                new CreateProjectCommand(
                    id: $requestContent['id'],
                    name: $requestContent['name'],
                    description: $requestContent['description'] ?? null,
                    topics: $requestContent['topics'] ?? null,
                    repository: $requestContent['repository'],
                    homepage: $requestContent['homepage'] ?? null,
                    archived: $requestContent['archived'],
                    lastPushedAt: new \DateTimeImmutable($requestContent['lastPushedAt'])
                )
            );

        $result = $sut->__invoke($this->createProjectRequest);
        $responseHeaders = $result->headers->all();

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($result->getStatusCode(), HttpStatusCode::HTTP_CREATED->value);
        $this->assertEmpty(json_decode($result->getContent(), true));
        $this->assertEquals(
            $responseHeaders['location'][0],
            sprintf('%s/api/projects/%d', $baseUrl, $requestContent['id'])
        );
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

        $sut = new CreateProjectController(
            commandBus: $this->commandBusMock->getMock(),
            queryBus: $this->createMock(QueryBus::class),
            params: $this->parameterBagMock->getMock()
        );

        $this->commandBusMock
            ->willThrowException(
                new CreateProjectCommand(
                    id: $requestContent['id'],
                    name: $requestContent['name'],
                    description: $requestContent['description'] ?? null,
                    topics: $requestContent['topics'] ?? null,
                    repository: $requestContent['repository'],
                    homepage: $requestContent['homepage'] ?? null,
                    archived: $requestContent['archived'],
                    lastPushedAt: new \DateTimeImmutable($requestContent['lastPushedAt'])
                ),
                $this->createMock(\Exception::class)
            );

        $this->expectException(\Exception::class);

        $sut->__invoke($this->createProjectRequest);
    }
}
