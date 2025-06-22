<?php

declare(strict_types=1);

namespace App\Projects\Domain;

use App\Shared\Domain\Contract\Collection;

/**
 * @template T of Project
 * @implements Collection<T>
 */
final class MappedProjects implements Collection
{
    /** @var array<string, Project> */
    private array $projects = [];

    public function __construct(Project ...$projects)
    {
        foreach ($projects as $project) {
            $this->projects[(string) $project->id()] = $project;
        }
    }

    /** @return Project[] */
    public function all(): array
    {
        return array_values($this->projects);
    }

    public function has(string $key): bool
    {
        return isset($this->projects[$key]);
    }

    /** @return Project|null */
    public function get(string $key): ?Project
    {
        return $this->projects[$key] ?? null;
    }
}
