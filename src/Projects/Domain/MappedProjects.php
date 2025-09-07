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
			/*
			 * Numeric string keys in arrays are converted to integers,
			 * see https://www.php.net/manual/en/language.types.array.php
			 */
			$key = '+' . (string) $project->id();
            $this->projects[$key] = $project;
        }
    }

    public function has(string $key): bool
    {
		if (!str_starts_with($key, '+')) {
			$key = '+' . $key;
		}

        return isset($this->projects[$key]);
    }

    /** @return Project|null */
    public function get(string $key): ?Project
    {
		if (!str_starts_with($key, '+')) {
			$key = '+' . $key;
		}

        return $this->projects[$key] ?? null;
    }

    /**
     * @return \Traversable<string, Project>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->projects);
    }
}
