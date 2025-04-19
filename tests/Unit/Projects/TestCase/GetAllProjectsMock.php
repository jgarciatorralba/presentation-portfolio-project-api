<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\TestCase;

use App\Projects\Domain\MappedProjects;
use App\Projects\Domain\Project;
use App\Projects\Domain\Service\GetAllProjects;

final class GetAllProjectsMock extends ProjectRepositoryServiceMock
{
    protected function getClassName(): string
    {
        return GetAllProjects::class;
    }

    public function shouldGetAllStoredProjects(Project ...$projects): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->willReturn(new MappedProjects(...$projects));
    }
}
