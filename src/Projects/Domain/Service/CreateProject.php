<?php

declare(strict_types=1);

namespace App\Projects\Domain\Service;

use App\Projects\Domain\Contract\ProjectRepository;
use App\Projects\Domain\Exception\ProjectAlreadyExistsException;
use App\Projects\Domain\Project;

final class CreateProject
{
    public function __construct(
        private readonly ProjectRepository $projectRepository
    ) {
    }

    public function __invoke(Project $project): void
    {
        $existingProject = $this->projectRepository->find($project->id());
        if ($existingProject !== null) {
            throw new ProjectAlreadyExistsException($project->id());
        }

        $this->projectRepository->create($project);
    }
}
