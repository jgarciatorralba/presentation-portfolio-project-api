<?php

declare(strict_types=1);

namespace App\Projects\Domain\Service;

use App\Projects\Domain\Contract\ProjectRepository;
use App\Projects\Domain\Exception\ProjectNotFoundException;
use App\Projects\Domain\Project;

final readonly class DeleteProject
{
    public function __construct(
        private ProjectRepository $projectRepository
    ) {
    }

    /** @throws ProjectNotFoundException */
    public function __invoke(Project $project): void
    {
        $existingProject = $this->projectRepository->find($project->id());
        if (!$existingProject instanceof Project) {
            throw new ProjectNotFoundException($project->id());
        }

        $this->projectRepository->delete($existingProject);
    }
}
