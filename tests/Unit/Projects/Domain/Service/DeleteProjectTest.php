<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\Domain\Service;

use App\Projects\Domain\Exception\ProjectNotFoundException;
use App\Projects\Domain\Project;
use App\Projects\Domain\Service\DeleteProject;
use Tests\Support\Builder\Projects\Domain\ProjectBuilder;
use Tests\Unit\Projects\TestCase\ProjectRepositoryMock;
use PHPUnit\Framework\TestCase;

final class DeleteProjectTest extends TestCase
{
    private ?Project $project;
    private ?ProjectRepositoryMock $projectRepositoryMock;
    private ?DeleteProject $sut;

    protected function setUp(): void
    {
        $this->project = ProjectBuilder::any()->build();
        $this->projectRepositoryMock = new ProjectRepositoryMock($this);
        $this->sut = new DeleteProject(
            projectRepository: $this->projectRepositoryMock->getMock()
        );
    }

    protected function tearDown(): void
    {
        $this->project = null;
        $this->projectRepositoryMock = null;
        $this->sut = null;
    }

    public function testItDeletesAProject(): void
    {
        $this->projectRepositoryMock
            ->shouldFindProject($this->project);
        $this->projectRepositoryMock
            ->shouldDeleteProject($this->project);

        $result = $this->sut->__invoke($this->project->id());

        $this->assertNull($result);
    }

    public function testItThrowsAnExceptionIfProjectDoesNotExist(): void
    {
        $this->projectRepositoryMock
            ->shouldNotFindProject($this->project);

        $this->expectException(ProjectNotFoundException::class);
        $this->expectExceptionMessage(
            sprintf(
                "Project with id '%s' could not be found.",
                $this->project->id()
            )
        );

        $this->sut->__invoke($this->project->id());
    }
}
