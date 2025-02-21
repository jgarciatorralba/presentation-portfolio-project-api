<?php

declare(strict_types=1);

namespace App\Projects\Domain\Service;

use App\Projects\Domain\Contract\ProjectRepository;
use App\Shared\Domain\Criteria\Criteria;

final readonly class GetProjectCountByCriteria
{
    public function __construct(
        private ProjectRepository $projectRepository
    ) {
    }

    public function __invoke(Criteria $criteria): int
    {
        return $this->projectRepository->countMatching($criteria);
    }
}
