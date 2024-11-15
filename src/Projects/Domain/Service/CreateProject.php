<?php

declare(strict_types=1);

namespace App\Projects\Domain\Service;

use App\Projects\Domain\Contract\ProjectRepository;
use App\Projects\Domain\Exception\ProjectAlreadyExistsException;
use App\Projects\Domain\Project;

final readonly class CreateProject
{
    public function __construct(
        private ProjectRepository $projectRepository
    ) {
    }

    /** @throws ProjectAlreadyExistsException */
    public function __invoke(Project $project): void
    {
        $existingProject = $this->projectRepository->find($project->id());
        if ($existingProject instanceof Project) {
            throw new ProjectAlreadyExistsException($project->id());
        }

        $this->projectRepository->create($project);
    }
}
