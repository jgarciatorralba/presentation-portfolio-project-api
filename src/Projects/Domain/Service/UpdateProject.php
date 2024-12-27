<?php

declare(strict_types=1);

namespace App\Projects\Domain\Service;

use App\Projects\Domain\Contract\ProjectRepository;
use App\Projects\Domain\Exception\ProjectNotFoundException;
use App\Projects\Domain\Project;
use App\Projects\Domain\ValueObject\ProjectId;

final readonly class UpdateProject
{
    public function __construct(
        private ProjectRepository $projectRepository
    ) {
    }

    /**
     * @throws ProjectNotFoundException
     */
    public function __invoke(ProjectId $id): void
    {
        $existingProject = $this->projectRepository->find($id);
        if (!$existingProject instanceof Project) {
            throw new ProjectNotFoundException($id);
        }

        $this->projectRepository->update($existingProject);
    }
}
