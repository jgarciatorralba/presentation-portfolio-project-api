<?php

declare(strict_types=1);

namespace App\Projects\Domain\Contract;

use App\Projects\Domain\ValueObject\DTO\ProjectDataDTO;

interface ProjectDataRetriever
{
    /**
     * @return list<ProjectDataDTO>
     */
    public function retrieve(): array;
}
