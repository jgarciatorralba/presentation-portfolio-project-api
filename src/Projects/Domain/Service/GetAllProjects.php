<?php

declare(strict_types=1);

namespace App\Projects\Domain\Service;

use App\Projects\Domain\Contract\ProjectRepository;
use App\Projects\Domain\MappedProjects;
use App\Projects\Domain\Project;

final readonly class GetAllProjects
{
    public function __construct(
        private ProjectRepository $projectRepository
    ) {
    }

    /** @return MappedProjects<Project> */
    public function __invoke(): MappedProjects
    {
        $projects = $this->projectRepository->findAll();

        return new MappedProjects(...$projects);
    }
}
