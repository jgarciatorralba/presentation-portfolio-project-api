<?php

declare(strict_types=1);

namespace App\Projects\Domain\Service;

use App\Projects\Domain\Contract\ExternalProjectRetriever;
use App\Projects\Domain\Project;

final readonly class RequestExternalProjects
{
    public function __construct(
        private ExternalProjectRetriever $externalProjectRetriever
    ) {
    }

    /**
     * @return array<int, Project>
     */
    public function __invoke(): array
    {
        $projects = $this->externalProjectRetriever->retrieve();

        $mappedProjects = [];
        foreach ($projects as $project) {
            $mappedProjects[$project->id()->value()] = $project;
        }

        return $mappedProjects;
    }
}
