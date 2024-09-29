<?php

declare(strict_types=1);

namespace App\Projects\Domain\Contract;

use App\Projects\Domain\Project;

interface ExternalProjectRetriever
{
    /** @return list<Project> */
    public function retrieve(): array;
}
