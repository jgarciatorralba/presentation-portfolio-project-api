<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\TestCase;

use App\Projects\Domain\Project;
use App\Projects\Domain\Service\GetAllProjects;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\IncompatibleReturnValueException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;

final class GetAllProjectsMock extends ProjectRepositoryServiceMock
{
    protected function getClassName(): string
    {
        return GetAllProjects::class;
    }

    /**
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     */
    public function shouldGetAllStoredProjects(Project ...$projects): void
    {
        $mappedProjects = [];
        foreach ($projects as $project) {
            $mappedProjects[$project->id()->value()] = $project;
        }

        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->willReturn($mappedProjects);
    }
}
