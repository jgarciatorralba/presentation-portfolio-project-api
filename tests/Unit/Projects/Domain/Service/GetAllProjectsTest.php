<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Service;

use App\Projects\Domain\MappedProjects;
use App\Projects\Domain\Service\GetAllProjects;
use App\Tests\Support\Builder\Projects\Domain\MappedProjectsBuilder;
use App\Tests\Unit\Projects\TestCase\ProjectRepositoryMock;
use PHPUnit\Framework\TestCase;

final class GetAllProjectsTest extends TestCase
{
    private ?MappedProjects $projects;
    private ?ProjectRepositoryMock $projectRepositoryMock;
    private ?GetAllProjects $sut;

    protected function setUp(): void
    {
        $this->projects = MappedProjectsBuilder::any()->build();
        $this->projectRepositoryMock = new ProjectRepositoryMock($this);
        $this->sut = new GetAllProjects(
            projectRepository: $this->projectRepositoryMock->getMock()
        );
    }

    protected function tearDown(): void
    {
        $this->projects = null;
        $this->projectRepositoryMock = null;
        $this->sut = null;
    }

    public function testItGetsAllProjectsMapped(): void
    {
        $this->projectRepositoryMock
            ->shouldFindAllProjects(...$this->projects->all());

        $result = $this->sut->__invoke();

        $this->assertEquals(
            $this->projects,
            $result
        );
    }
}
