<?php

declare(strict_types=1);

namespace App\Tests\Builder\Projects\Domain;

use App\Projects\Domain\Exception\InvalidProjectRepositoryUrlException;
use App\Projects\Domain\Project;
use App\Projects\Domain\MappedProjects;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class MappedProjectsBuilder implements BuilderInterface
{
    private const int MIN_PROJECTS = 1;
    private const int MAX_PROJECTS = 100;

    /** @var list<Project> */
    private array $projects = [];

    private function __construct(
        Project ...$projects
    ) {
        $this->projects = $projects;
    }

    /**
     * @throws InvalidProjectRepositoryUrlException
     * @throws \InvalidArgumentException
     */
    public static function any(): self
    {
        $amount = FakeValueGenerator::integer(
            min: self::MIN_PROJECTS,
            max: self::MAX_PROJECTS
        );

        $projects = [];

        $i = 0;
        while ($i < $amount) {
            $project = ProjectBuilder::any()->build();
            if (!in_array($project->id()->value(), array_keys($projects))) {
                $projects[$project->id()->value()] = $project;
                $i++;
            }
        }

        return new self(...array_values($projects));
    }

    public function withProjects(Project ...$projects): self
    {
        return new self(...$projects);
    }

    /** @return MappedProjects<Project> */
    public function build(): MappedProjects
    {
        return new MappedProjects(...$this->projects);
    }
}
