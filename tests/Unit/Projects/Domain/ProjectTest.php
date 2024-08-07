<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain;

use App\Projects\Domain\Project;
use App\Tests\Unit\Projects\Domain\Factory\ProjectFactory;
use PHPUnit\Framework\TestCase;

final class ProjectTest extends TestCase
{
    public function testProjectIsCreated(): void
    {
        $projectCreated = ProjectFactory::create();

        $projectAsserted = Project::create(
            id: $projectCreated->id(),
            details: $projectCreated->details(),
            urls: $projectCreated->urls(),
            archived: $projectCreated->archived(),
            lastPushedAt: $projectCreated->lastPushedAt(),
            createdAt: $projectCreated->createdAt(),
            updatedAt: $projectCreated->updatedAt()
        );

        $this->assertEquals($projectCreated, $projectAsserted);
    }
}
