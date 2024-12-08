<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Service;

use App\Projects\Domain\Project;
use App\Projects\Domain\Service\GetAllProjects;
use App\Tests\Builder\Projects\Domain\ProjectBuilder;
use App\Tests\Unit\Projects\TestCase\ProjectRepositoryMock;
use PHPUnit\Framework\TestCase;

final class GetAllProjectsTest extends TestCase
{
	/** @var Project[] $projects */
	private ?array $projects;
	private ?ProjectRepositoryMock $projectRepositoryMock;
	private ?GetAllProjects $sut;

	protected function setUp(): void
	{
		$this->projects = ProjectBuilder::buildMany();
		$this->projectRepositoryMock = new ProjectRepositoryMock();
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
			->shouldFindAllProjects(...$this->projects);

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
