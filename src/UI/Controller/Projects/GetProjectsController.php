<?php

declare(strict_types=1);

namespace App\UI\Controller\Projects;

use App\Projects\Application\Query\GetProjects\GetProjectsQuery;
use App\Shared\Utils;
use App\UI\Controller\BaseController;
use App\UI\Request\Projects\GetProjectsRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GetProjectsController extends BaseController
{
    public function __invoke(GetProjectsRequest $request): Response
    {
        $pageSize = $request->get('pageSize')
            ? intval($request->get('pageSize'))
            : null;
        $maxCreatedAt = $request->get('maxCreatedAt')
            ? Utils::stringToDate($request->get('maxCreatedAt'))
            : null;

        $response = $this->ask(
            new GetProjectsQuery(
                pageSize: $pageSize,
                maxCreatedAt: $maxCreatedAt
            )
        );

        return new JsonResponse($response->data(), Response::HTTP_OK);
    }
}
