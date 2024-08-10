<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\TestCase;

use App\Projects\Domain\Project;
use App\Projects\Domain\Service\CreateProject;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class CreateProjectMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return CreateProject::class;
    }

    public function shouldCreateProject(Project $project): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($project);
    }
}
