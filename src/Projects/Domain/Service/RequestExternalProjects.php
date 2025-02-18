<?php

declare(strict_types=1);

namespace App\Projects\Domain\Service;

use App\Projects\Domain\Contract\ExternalProjectRetriever;
use App\Projects\Domain\MappedProjects;
use App\Projects\Domain\Project;

final readonly class RequestExternalProjects
{
    public function __construct(
        private ExternalProjectRetriever $externalProjectRetriever
    ) {
    }

    /** @return MappedProjects<Project> */
    public function __invoke(): MappedProjects
    {
        return $this->externalProjectRetriever->retrieve();
    }
}
