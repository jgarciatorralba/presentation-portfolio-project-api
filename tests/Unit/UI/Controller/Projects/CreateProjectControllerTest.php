<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Controller\Projects;

use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Service\LocalDateTimeZoneConverter;
use App\Tests\Unit\UI\TestCase\CommandBusMock;
use App\UI\Controller\Projects\CreateProjectController;
use App\UI\Request\Projects\CreateProjectRequest;
use App\UI\Validation\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class CreateProjectControllerTest extends TestCase
{
    private ?CommandBusMock $commandBusMock;
    private ?CreateProjectController $sut;
    private ?CreateProjectRequest $createProjectRequest;

    protected function setUp(): void
    {
        $this->commandBusMock = new CommandBusMock();
        $this->sut = new CreateProjectController(
            commandBus: $this->commandBusMock->getMock(),
            queryBus: $this->createMock(QueryBus::class)
        );

        $requestStack = new RequestStack();
        $requestStack->push(new Request(
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
            request: $requestStack,
            dateTimeConverter: $this->createMock(LocalDateTimeZoneConverter::class)
        );
    }

    protected function tearDown(): void
    {
        $this->commandBusMock = null;
        $this->sut = null;
        $this->createProjectRequest = null;
    }

    public function testItReturnsResponse(): void
    {
        $this->commandBusMock
            ->shouldDispatchCommand();

        $result = $this->sut->__invoke($this->createProjectRequest);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($result->getStatusCode(), HttpResponse::HTTP_CREATED);
        $this->assertIsArray(json_decode($result->getContent(), true));
        $this->assertArrayHasKey('id', json_decode($result->getContent(), true));
    }

    public function testItThrowsException(): void
    {
        $this->commandBusMock
            ->willThrowException($this->createMock(\Exception::class));

        $this->expectException(\Exception::class);

        $this->sut->__invoke($this->createProjectRequest);
    }
}
