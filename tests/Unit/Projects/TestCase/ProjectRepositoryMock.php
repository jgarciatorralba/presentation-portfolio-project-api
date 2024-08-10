<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\TestCase;

use App\Projects\Domain\Contract\ProjectRepository;
use App\Projects\Domain\Project;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class ProjectRepositoryMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return ProjectRepository::class;
    }

    public function shouldCreateProject(Project $project): void
    {
        $this->mock
            ->expects($this->once())
            ->method('create')
            ->with($project);
    }
}
