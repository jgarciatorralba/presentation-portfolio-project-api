<?php

declare(strict_types=1);

namespace App\Projects\Application\Query\GetProjects;

use App\Shared\Domain\Bus\Query\QueryHandler;

final class GetProjectsQueryHandler implements QueryHandler
{
    public function __construct()
    {
    }

    public function __invoke(GetProjectsQuery $query): GetProjectsResponse
    {
        $projects = [
            ['test' => 'Hello World!']
        ];

        return new GetProjectsResponse([
            'projects' => $projects,
            'count' => count($projects)
        ]);
    }
}
