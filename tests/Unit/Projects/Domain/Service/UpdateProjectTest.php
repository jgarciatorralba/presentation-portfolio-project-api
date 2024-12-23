<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Service;

use App\Projects\Domain\Exception\ProjectNotFoundException;
use App\Projects\Domain\Project;
use App\Projects\Domain\Service\UpdateProject;
use App\Tests\Builder\Projects\Domain\ProjectBuilder;
use App\Tests\Unit\Projects\TestCase\ProjectRepositoryMock;
use PHPUnit\Framework\TestCase;

final class UpdateProjectTest extends TestCase
{
    private ?Project $project;
    private ?ProjectRepositoryMock $projectRepositoryMock;
    private ?UpdateProject $sut;

    protected function setUp(): void
    {
        $this->project = ProjectBuilder::any()->build();
        $this->projectRepositoryMock = new ProjectRepositoryMock($this);
        $this->sut = new UpdateProject(
            projectRepository: $this->projectRepositoryMock->getMock()
        );
    }

    protected function tearDown(): void
    {
        $this->project = null;
        $this->projectRepositoryMock = null;
        $this->sut = null;
    }

    public function testItSynchronizesWithAProjectAndUpdatesIt(): void
    {
        $existingProject = $this->createMock(Project::class);
        $existingProject
            ->method('id')
            ->willReturn($this->project->id());

        $this->projectRepositoryMock
            ->shouldFindProject($existingProject);

        $existingProject
            ->expects($this->once())
            ->method('synchronizeWith')
            ->with($this->project);

        $this->projectRepositoryMock
            ->shouldUpdateProject($existingProject);

        $result = $this->sut->__invoke($this->project);

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

        $this->sut->__invoke($this->project);
    }
}
