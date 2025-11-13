<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\TestCase;

use App\Projects\Domain\Exception\ProjectAlreadyExistsException;
use App\Projects\Domain\Project;
use App\Projects\Domain\Service\CreateProject;

final class CreateProjectMock extends ProjectRepositoryServiceMock
{
    protected function getClassName(): string
    {
        return CreateProject::class;
    }

    public function shouldCreateProject(Project $expected): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->testCase->callback(
                function (Project $actual) use ($expected): true {
                    $this->assertProjectsAreEqual($expected, $actual);
                    return true;
                }
            ));
    }

    public function shouldThrowException(
        Project $expected
    ): void {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($expected)
            ->willThrowException(
                new ProjectAlreadyExistsException($expected->id())
            );
    }
}
