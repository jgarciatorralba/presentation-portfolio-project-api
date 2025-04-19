<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain;

use App\Projects\Domain\MappedProjects;
use App\Projects\Domain\Project;
use App\Tests\Builder\Projects\Domain\ProjectBuilder;
use PHPUnit\Framework\TestCase;

final class MappedProjectsTest extends TestCase
{
    /** @var list<Project> */
    private ?array $projects;
    private ?MappedProjects $mappedProjects;

    protected function setUp(): void
    {
        $this->projects = [
            ProjectBuilder::any()->build(),
        ];

        $this->mappedProjects = new MappedProjects(...$this->projects);
    }

    protected function tearDown(): void
    {
        $this->projects = null;
        $this->mappedProjects = null;
    }

    public function testItCanBeCreatedFromProjects(): void
    {
        $this->assertInstanceOf(MappedProjects::class, $this->mappedProjects);
    }

    public function testItHasAllProjects(): void
    {
        $this->assertCount(count($this->projects), $this->mappedProjects->all());
    }

    public function testItCanCheckIfProjectExists(): void
    {
        $existingProject = $this->projects[0];

        $this->assertTrue($this->mappedProjects->has((string) $existingProject->id()));
        $this->assertFalse($this->mappedProjects->has('non-existing-id'));
    }

    public function testItGetsProjectById(): void
    {
        $existingProject = $this->projects[0];

        $foundProject = $this->mappedProjects->get((string) $existingProject->id());
        $notFoundProject = $this->mappedProjects->get('non-existing-id');

        $this->assertEquals($existingProject, $foundProject);
        $this->assertNull($notFoundProject);
    }
}
