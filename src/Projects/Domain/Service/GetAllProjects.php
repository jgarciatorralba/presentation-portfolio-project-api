<?php

declare(strict_types=1);

namespace App\Projects\Domain\Service;

use App\Projects\Domain\Contract\ProjectRepository;
use App\Projects\Domain\Project;

final class GetAllProjects
{
    public function __construct(
        private readonly ProjectRepository $projectRepository
    ) {
    }

    /**
     * @return array<int, Project>
     * */
    public function __invoke(): array
    {
        $projects = $this->projectRepository->findAll();

        $mappedProjects = [];
        foreach ($projects as $project) {
            $mappedProjects[$project->id()] = $project;
        }

        return $mappedProjects;
    }
}
