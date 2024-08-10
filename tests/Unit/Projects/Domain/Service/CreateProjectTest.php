<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\Domain\Service;

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

    public function testCreateProject(): void
    {
        $project = ProjectFactory::create();
        $this->projectRepositoryMock->shouldCreateProject($project);

        $service = new CreateProject(
            projectRepository: $this->projectRepositoryMock->getMock()
        );
        $result = $service->__invoke($project);

        $this->assertNull($result);
    }
}
