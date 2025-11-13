<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\Domain\Service;

use App\Projects\Domain\MappedProjects;
use App\Projects\Domain\Service\RequestExternalProjects;
use Tests\Support\Builder\Projects\Domain\MappedProjectsBuilder;
use Tests\Unit\Projects\TestCase\ExternalProjectRetrieverMock;
use PHPUnit\Framework\TestCase;

final class RequestExternalProjectsTest extends TestCase
{
    private ?MappedProjects $projects;
    private ?ExternalProjectRetrieverMock $externalProjectRetrieverMock;
    private ?RequestExternalProjects $sut;

    protected function setUp(): void
    {
        $this->projects = MappedProjectsBuilder::any()->build();
        $this->externalProjectRetrieverMock = new ExternalProjectRetrieverMock($this);
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
            ->shouldRetrieveProjects(...$this->projects->getIterator());

        $result = $this->sut->__invoke();

        $this->assertEquals(
            $this->projects,
            $result
        );
    }
}
