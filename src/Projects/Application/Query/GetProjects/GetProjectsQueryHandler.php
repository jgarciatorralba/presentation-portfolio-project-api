<?php

declare(strict_types=1);

namespace App\Projects\Application\Query\GetProjects;

use App\Projects\Domain\Service\GetProjectCountByCriteria;
use App\Projects\Domain\Service\GetProjectsByCriteria;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\Criteria\PushedBeforeDateTimeCriteria;

final readonly class GetProjectsQueryHandler implements QueryHandler
{
    public function __construct(
        private GetProjectsByCriteria $getProjectsByCriteria,
        private GetProjectCountByCriteria $getProjectCountByCriteria
    ) {
    }

    public function __invoke(GetProjectsQuery $query): GetProjectsResponse
    {
        $limit = $query->pageSize();
        $maxPushedAt = $query->maxPushedAt() ?? new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

        $projects = $this->getProjectsByCriteria->__invoke(
            new PushedBeforeDateTimeCriteria($maxPushedAt, $limit)
        );

        $total = $this->getProjectCountByCriteria->__invoke(
            new PushedBeforeDateTimeCriteria($maxPushedAt)
        );

        return new GetProjectsResponse($total, ...$projects);
    }
}
