<?php

declare(strict_types=1);

namespace App\Projects\Application\Query\GetProjects;

use App\Projects\Domain\Project;
use App\Shared\Domain\Bus\Query\Response;

final readonly class GetProjectsResponse implements Response
{
	/** @var Project[] */
	private array $projects;

    public function __construct(Project ...$projects) {
		$this->projects = $projects;
    }

	/** @return array<string, mixed> */
    public function data(): array
    {
        return [
			'projects' => array_map(
				fn (Project $project): array => $project->toArray(),
				$this->projects
			),
			'count' => count($this->projects)
		];
    }
}
