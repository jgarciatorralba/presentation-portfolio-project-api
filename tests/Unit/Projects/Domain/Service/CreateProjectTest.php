<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\Domain\Service;

use App\Projects\Domain\Exception\ProjectAlreadyExistsException;
use App\Projects\Domain\Project;
use App\Projects\Domain\Service\CreateProject;
use Tests\Support\Builder\Projects\Domain\ProjectBuilder;
use Tests\Unit\Projects\TestCase\ProjectRepositoryMock;
use PHPUnit\Framework\TestCase;

final class CreateProjectTest extends TestCase
{
    private ?Project $project;
    private ?ProjectRepositoryMock $projectRepositoryMock;
    private ?CreateProject $sut;

    protected function setUp(): void
    {
        $this->project = ProjectBuilder::any()->build();
        $this->projectRepositoryMock = new ProjectRepositoryMock($this);
        $this->sut = new CreateProject(
            projectRepository: $this->projectRepositoryMock->getMock()
        );
    }

    protected function tearDown(): void
    {
        $this->project = null;
        $this->projectRepositoryMock = null;
        $this->sut = null;
    }

    public function testItCreatesAProject(): void
    {
        $this->projectRepositoryMock
            ->shouldNotFindProject($this->project);
        $this->projectRepositoryMock
            ->shouldCreateProject($this->project);

        $result = $this->sut->__invoke($this->project);

        $this->assertNull($result);
    }

    public function testItThrowsAnExceptionIfProjectAlreadyExists(): void
    {
        $this->projectRepositoryMock
            ->shouldFindProject($this->project);

        $this->expectException(ProjectAlreadyExistsException::class);
        $this->expectExceptionMessage(
            sprintf(
                "Project with id '%s' already exists.",
                $this->project->id()
            )
        );

        $this->sut->__invoke($this->project);
    }
}
