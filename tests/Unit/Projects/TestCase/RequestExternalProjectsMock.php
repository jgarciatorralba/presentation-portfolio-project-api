<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\TestCase;

use App\Projects\Domain\MappedProjects;
use App\Projects\Domain\Project;
use App\Projects\Domain\Service\RequestExternalProjects;
use Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class RequestExternalProjectsMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return RequestExternalProjects::class;
    }

    public function shouldRequestExternalProjects(Project ...$projects): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->willReturn(new MappedProjects(...$projects));
    }
}
