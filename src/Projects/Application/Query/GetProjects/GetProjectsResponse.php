<?php

declare(strict_types=1);

namespace App\Projects\Application\Query\GetProjects;

use App\Projects\Domain\Project;
use App\Shared\Domain\Bus\Query\Response;

final readonly class GetProjectsResponse implements Response
{
    /** @var Project[] */
    private array $projects;

    public function __construct(
        private int $totalCount,
        Project ...$projects
    ) {
        $this->projects = $projects;
    }

    public function count(): int
    {
        return count($this->projects);
    }

    public function totalCount(): int
    {
        return $this->totalCount;
    }

    /** @return array<string, mixed> */
    #[\Override]
    public function data(): array
    {
        return [
            'projects' => array_map(
                fn (Project $project): array => $project->toArray(),
                $this->projects
            ),
            'count' => $this->count(),
        ];
    }
}
