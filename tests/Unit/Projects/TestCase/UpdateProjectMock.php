<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\TestCase;

use App\Projects\Domain\Exception\ProjectNotFoundException;
use App\Projects\Domain\Project;
use App\Projects\Domain\Service\UpdateProject;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;

final class UpdateProjectMock extends ProjectRepositoryServiceMock
{
    protected function getClassName(): string
    {
        return UpdateProject::class;
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     */
    public function shouldUpdateProject(Project $expected): void
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

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     */
    public function shouldThrowException(
        Project $expected
    ): void {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($expected)
            ->willThrowException(
                new ProjectNotFoundException($expected->id())
            );
    }
}
