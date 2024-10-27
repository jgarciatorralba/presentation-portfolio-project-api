<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Bus\Event;

use App\Projects\Domain\Bus\Event\ProjectModifiedEvent;
use App\Projects\Domain\Project;
use App\Tests\Builder\Projects\Domain\ProjectBuilder;
use PHPUnit\Framework\TestCase;

final class ProjectModifiedEventTest extends TestCase
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
        $event = new ProjectModifiedEvent($this->project);

        self::assertSame($this->project, $event->project());
        self::assertSame('projects.domain.project_modified', $event::eventType());
    }
}
