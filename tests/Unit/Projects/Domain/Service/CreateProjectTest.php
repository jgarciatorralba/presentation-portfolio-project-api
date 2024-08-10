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

        $service = new CreateProject(
            projectRepository: $this->projectRepositoryMock->getMock()
        );
        $result = $service->__invoke($project);

        $this->assertNull($result);
    }

    public function testItThrowsAnAlreadyExistingException(): void
    {
        $project = ProjectFactory::create();
        $this->projectRepositoryMock->shouldFindProject($project);

        $service = new CreateProject(
            projectRepository: $this->projectRepositoryMock->getMock()
        );

        $this->expectException(ProjectAlreadyExistsException::class);
        $this->expectExceptionMessage(
            sprintf(
                "Project with id '%s' already exists.",
                $project->id()
            )
        );

        $service->__invoke($project);
    }
}
