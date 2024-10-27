<?php

declare(strict_types=1);

namespace App\Projects\Domain\Service;

use App\Projects\Domain\Contract\ProjectRepository;
use App\Projects\Domain\Project;
use App\Shared\Domain\Criteria\Criteria;

final readonly class GetProjectsByCriteria
{
    public function __construct(
        private ProjectRepository $projectRepository
    ) {
    }

    /**
     * @return Project[]
     */
    public function __invoke(Criteria $criteria): array
    {
        return $this->projectRepository->matching($criteria);
    }
}
