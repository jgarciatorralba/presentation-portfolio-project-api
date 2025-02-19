<?php

declare(strict_types=1);

namespace App\Projects\Application\Query\GetProjects;

use App\Projects\Domain\Service\GetProjectsByCriteria;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\Criteria\PushedBeforeDateTimeCriteria;

final readonly class GetProjectsQueryHandler implements QueryHandler
{
    public function __construct(
        private GetProjectsByCriteria $getProjectsByCriteria
    ) {
    }

    public function __invoke(GetProjectsQuery $query): GetProjectsResponse
    {
        $limit = $query->pageSize() > 0 ? $query->pageSize() : null;
        $maxPushedAt = $query->maxPushedAt() ?? new \DateTimeImmutable();

        $projects = $this->getProjectsByCriteria->__invoke(
            new PushedBeforeDateTimeCriteria($maxPushedAt, $limit)
        );

        return new GetProjectsResponse(...$projects);
    }
}
