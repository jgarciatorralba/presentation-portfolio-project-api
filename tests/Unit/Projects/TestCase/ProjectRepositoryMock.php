<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\TestCase;

use App\Projects\Domain\Contract\ProjectRepository;
use App\Projects\Domain\Project;
use App\Shared\Domain\Criteria\Criteria;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\IncompatibleReturnValueException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;

final class ProjectRepositoryMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return ProjectRepository::class;
    }

    /**
     * @throws Exception
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     */
    public function shouldFindProject(Project $project): void
    {
        $this->mock
            ->expects($this->once())
            ->method('find')
            ->with($project->id())
            ->willReturn($project);
    }

    /**
     * @throws Exception
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     */
    public function shouldNotFindProject(Project $project): void
    {
        $this->mock
            ->expects($this->once())
            ->method('find')
            ->with($project->id())
            ->willReturn(null);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     */
    public function shouldCreateProject(Project $project): void
    {
        $this->mock
            ->expects($this->once())
            ->method('create')
            ->with($project);
    }

    /**
     * @throws Exception
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     */
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

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     */
    public function shouldDeleteProject(Project $project): void
    {
        $this->mock
            ->expects($this->once())
            ->method('delete')
            ->with($project);
    }

    /**
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     */
    public function shouldFindAllProjects(Project ...$projects): void
    {
        $this->mock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($projects);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     */
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
