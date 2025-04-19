<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\TestCase;

use App\Projects\Domain\Exception\ProjectNotFoundException;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Projects\Domain\Service\UpdateProject;

final class UpdateProjectMock extends ProjectRepositoryServiceMock
{
    protected function getClassName(): string
    {
        return UpdateProject::class;
    }

    public function shouldUpdateProject(ProjectId $expected): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->testCase->callback(
                fn(ProjectId $actual): bool => $actual->equals($expected)
            ));
    }

    public function shouldThrowException(
        ProjectId $expected
    ): void {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($expected)
            ->willThrowException(
                new ProjectNotFoundException($expected)
            );
    }
}
