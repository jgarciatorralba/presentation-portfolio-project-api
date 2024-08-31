<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain;

use App\Projects\Domain\Project;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Tests\Unit\Projects\Domain\Factory\ProjectFactory;
use PHPUnit\Framework\TestCase;

final class ProjectTest extends TestCase
{
    private ?Project $project;

    protected function setUp(): void
    {
        $this->project = ProjectFactory::create();
    }

    protected function tearDown(): void
    {
        $this->project = null;
    }

    public function testProjectIsCreated(): void
    {
        $projectAsserted = Project::create(
            id: $this->project->id(),
            details: $this->project->details(),
            urls: $this->project->urls(),
            archived: $this->project->archived(),
            lastPushedAt: $this->project->lastPushedAt(),
            createdAt: $this->project->createdAt(),
            updatedAt: $this->project->updatedAt()
        );

        $this->assertEquals($this->project, $projectAsserted);
    }

    public function testProjectExtendsAggregateRoot(): void
    {
        $this->assertInstanceOf(AggregateRoot::class, $this->project);

        $this->assertTrue(method_exists($this->project, 'pullEvents'));
        $this->assertTrue(method_exists($this->project, 'recordEvent'));
    }

    public function testProjectIsConvertedToArray(): void
    {
        $projectArray = $this->project->toArray();

        $this->assertIsArray($projectArray);
        $this->assertCount(9, array_keys($projectArray));
        $this->assertEquals($this->project->id(), $projectArray['id']);
        $this->assertEquals($this->project->details()->name(), $projectArray['name']);
        $this->assertEquals($this->project->details()->description(), $projectArray['description']);
        $this->assertEquals($this->project->details()->topics(), $projectArray['topics']);
        $this->assertEquals($this->project->urls()->repository(), $projectArray['repository']);
        $this->assertEquals($this->project->urls()->homepage(), $projectArray['homepage']);
        $this->assertEquals($this->project->archived(), $projectArray['archived']);
        $this->assertEquals(
            $this->project->lastPushedAt()->format(\DateTimeInterface::ATOM),
            $projectArray['lastPushedAt']
        );
        $this->assertEquals(
            $this->project->createdAt()->format(\DateTimeInterface::ATOM),
            $projectArray['createdAt']
        );
    }
}
