<?php

declare(strict_types=1);

namespace App\Projects\Application\Query\GetProjects;

use App\Projects\Domain\Service\GetProjectsByCriteria;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\Criteria\CreatedBeforeDateTimeCriteria;

final class GetProjectsQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly GetProjectsByCriteria $getProjectsByCriteria
    ) {
    }

    public function __invoke(GetProjectsQuery $query): GetProjectsResponse
    {
        $limit = $query->pageSize() > 0 ? $query->pageSize() : null;
        $maxCreatedAt = $query->maxCreatedAt() ?? new \DateTimeImmutable();

        $projects = $this->getProjectsByCriteria->__invoke(
            new CreatedBeforeDateTimeCriteria($maxCreatedAt, $limit)
        );

        $projects = array_map(
            fn (AggregateRoot $project) => $project->toArray(),
            $projects
        );

        return new GetProjectsResponse([
            'projects' => $projects,
            'count' => count($projects)
        ]);
    }
}
