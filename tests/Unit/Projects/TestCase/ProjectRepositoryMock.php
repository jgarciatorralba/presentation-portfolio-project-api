<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\TestCase;

use App\Projects\Domain\Contract\ProjectRepository;
use App\Projects\Domain\Project;
use App\Shared\Domain\Criteria\Criteria;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class ProjectRepositoryMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return ProjectRepository::class;
    }

    public function shouldFindProject(Project $project): void
    {
        $this->mock
            ->expects($this->once())
            ->method('find')
            ->with($project->id())
            ->willReturn($project);
    }

    public function shouldNotFindProject(Project $project): void
    {
        $this->mock
            ->expects($this->once())
            ->method('find')
            ->with($project->id())
            ->willReturn(null);
    }

    public function shouldCreateProject(Project $project): void
    {
        $this->mock
            ->expects($this->once())
            ->method('create')
            ->with($project);
    }

    public function shouldFindProjectsMatchingCriteria(
        Criteria $criteria,
        Project ...$projects
    ): void {
        $this->mock
            ->expects($this->once())
            ->method('matching')
            ->with($criteria)
            ->willReturn($projects);
    }

    public function shouldDeleteProject(Project $project): void
    {
        $this->mock
            ->expects($this->once())
            ->method('delete')
            ->with($project);
    }

    public function shouldFindAllProjects(Project ...$projects): void
    {
        $this->mock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($projects);
    }

    public function shouldUpdateProject(Project $project): void
    {
        $this->mock
            ->expects($this->once())
            ->method('update')
            ->with($project);
    }

	public function shouldCountProjectsMatchingCriteria(
		Criteria $criteria,
		int $expectedCount
	): void {
		$this->mock
			->expects($this->once())
			->method('countMatching')
			->with($criteria)
			->willReturn($expectedCount);
	}
}
