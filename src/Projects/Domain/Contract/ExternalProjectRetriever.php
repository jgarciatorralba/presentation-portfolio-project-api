<?php

declare(strict_types=1);

namespace App\Projects\Domain\Contract;

use App\Projects\Domain\Project;
use App\Projects\Domain\MappedProjects;

interface ExternalProjectRetriever
{
    /** @return MappedProjects<Project> */
    public function retrieve(): MappedProjects;
}
