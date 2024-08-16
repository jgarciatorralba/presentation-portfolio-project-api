<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\Domain\Service;

use App\Projects\Domain\Exception\ProjectAlreadyExistsException;
use App\Projects\Domain\Service\CreateProject;
use App\Tests\Unit\Projects\Domain\Factory\ProjectFactory;
use App\Tests\Unit\Projects\TestCase\ProjectRepositoryMock;
use PHPUnit\Framework\TestCase;

final class CreateProjectTest extends TestCase
{
    private ?ProjectRepositoryMock $projectRepositoryMock;

    protected function setUp(): void
    {
        $this->projectRepositoryMock = new ProjectRepositoryMock($this);
    }

    protected function tearDown(): void
    {
        $this->projectRepositoryMock = null;
    }

    public function testItCreatesAProject(): void
    {
        $project = ProjectFactory::create();

        $this->projectRepositoryMock->shouldNotFindProject($project->id());
        $this->projectRepositoryMock->shouldCreateProject($project);

        $sut = new CreateProject(
            projectRepository: $this->projectRepositoryMock->getMock()
        );
        $result = $sut->__invoke($project);

        $this->assertNull($result);
    }

    public function testItThrowsAnExceptionIfProjectAlreadyExists(): void
    {
        $project = ProjectFactory::create();
        $this->projectRepositoryMock->shouldFindProject($project);

        $sut = new CreateProject(
            projectRepository: $this->projectRepositoryMock->getMock()
        );

        $this->expectException(ProjectAlreadyExistsException::class);
        $this->expectExceptionMessage(
            sprintf(
                "Project with id '%s' already exists.",
                $project->id()
            )
        );

        $sut->__invoke($project);
    }
}
