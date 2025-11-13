<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\Domain;

use App\Projects\Domain\MappedProjects;
use App\Projects\Domain\Project;
use Tests\Support\Builder\Projects\Domain\ProjectBuilder;
use PHPUnit\Framework\TestCase;

final class MappedProjectsTest extends TestCase
{
    /** @var list<Project> */
    private ?array $projects;

    protected function setUp(): void
    {
        $this->projects = [
            ProjectBuilder::any()->build(),
        ];
    }

    protected function tearDown(): void
    {
        $this->projects = null;
    }

    public function testTheyCanBeCreatedFromProjects(): void
    {
        $this->assertInstanceOf(
            MappedProjects::class,
            new MappedProjects(...$this->projects)
        );
    }

    public function testTheyContainAllProjects(): void
    {
        $mappedProjects = new MappedProjects(...$this->projects);

        $this->assertCount(
            count($this->projects),
            $mappedProjects->getIterator()
        );
    }

    public function testTheyCanTellWhetherTheyHaveProject(): void
    {
        $mappedProjects = new MappedProjects(...$this->projects);

        $this->assertTrue($mappedProjects->has((string) $this->projects[0]->id()));
        $this->assertFalse($mappedProjects->has('non-existing-id'));
    }

    public function testTheyCanRetrieveProjectById(): void
    {
        $mappedProjects = new MappedProjects(...$this->projects);

        $foundProject = $mappedProjects->get((string) $this->projects[0]->id());
        $notFoundProject = $mappedProjects->get('non-existing-id');

        $this->assertEquals($this->projects[0], $foundProject);
        $this->assertNull($notFoundProject);
    }

    public function testTheyAreACollection(): void
    {
        $mappedProjects = new MappedProjects(...$this->projects);
        $iterator = $mappedProjects->getIterator();

        $this->assertInstanceOf(\Traversable::class, $iterator);
        $this->assertCount(count($this->projects), $iterator);

        foreach ($iterator as $key => $project) {
            $this->assertIsString($key);
            $this->assertInstanceOf(Project::class, $project);
        }
    }
}
