<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain;

use App\Projects\Domain\Project;
use PHPUnit\Framework\TestCase;

final class ProjectTest extends TestCase
{
    public function testProjectIsCreated(): void
    {
        $projectCreated = ProjectFactory::create();

        $projectAsserted = Project::create(
            $projectCreated->id(),
            $projectCreated->details(),
            $projectCreated->urls(),
            $projectCreated->archived(),
            $projectCreated->lastPushed(),
            $projectCreated->createdAt(),
            $projectCreated->updatedAt()
        );

        $this->assertEquals($projectCreated, $projectAsserted);
    }
}
