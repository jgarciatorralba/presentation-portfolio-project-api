<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Service;

use App\Projects\Domain\Project;
use App\Projects\Domain\Service\RequestExternalProjects;
use App\Tests\Builder\Projects\Domain\ProjectBuilder;
use App\Tests\Unit\Projects\TestCase\ExternalProjectRetrieverMock;
use PHPUnit\Framework\TestCase;

final class RequestExternalProjectsTest extends TestCase
{
    /** @var Project[] $projects */
    private ?array $projects;
    private ?ExternalProjectRetrieverMock $externalProjectRetrieverMock;
    private ?RequestExternalProjects $sut;

    protected function setUp(): void
    {
        $this->projects = ProjectBuilder::buildMany();
        $this->externalProjectRetrieverMock = new ExternalProjectRetrieverMock();
        $this->sut = new RequestExternalProjects(
            externalProjectRetriever: $this->externalProjectRetrieverMock->getMock()
        );
    }

    protected function tearDown(): void
    {
        $this->projects = null;
        $this->externalProjectRetrieverMock = null;
        $this->sut = null;
    }

    public function testItRequestsExternalProjects(): void
    {
        $this->externalProjectRetrieverMock
            ->shouldRetrieveProjects(...$this->projects);

        $result = $this->sut->__invoke();

        $mappedProjects = array_reduce(
            $this->projects,
            static function (array $carry, Project $project): array {
                $carry[$project->id()->value()] = $project;
                return $carry;
            },
            []
        );

        $this->assertEquals(
            $mappedProjects,
            $result
        );
    }
}
