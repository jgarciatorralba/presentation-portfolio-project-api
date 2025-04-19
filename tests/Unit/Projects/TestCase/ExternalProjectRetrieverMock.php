<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\TestCase;

use App\Projects\Domain\Contract\ExternalProjectRetriever;
use App\Projects\Domain\MappedProjects;
use App\Projects\Domain\Project;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class ExternalProjectRetrieverMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return ExternalProjectRetriever::class;
    }

    public function shouldRetrieveProjects(Project ...$projects): void
    {
        $this->mock
            ->expects($this->once())
            ->method('retrieve')
            ->willReturn(new MappedProjects(...$projects));
    }
}
