<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Bus\Event;

use App\Projects\Domain\Bus\Event\ProjectRemovedEvent;
use App\Projects\Domain\Project;
use App\Tests\Builder\Projects\Domain\ProjectBuilder;
use PHPUnit\Framework\TestCase;

final class ProjectRemovedEventTest extends TestCase
{
    private ?Project $project;

    protected function setUp(): void
    {
        $this->project = ProjectBuilder::any()->build();
    }

    protected function tearDown(): void
    {
        $this->project = null;
    }

    public function testItIsCreated(): void
    {
        $event = new ProjectRemovedEvent($this->project->id());

        self::assertSame(
            (string) $this->project->id(),
            $event->aggregateId()
        );
        self::assertSame('projects.domain.project_removed', $event::eventType());
    }
}
