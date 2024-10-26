<?php

declare(strict_types=1);

namespace App\UI\Controller\Projects;

use App\Projects\Application\Query\GetProjects\GetProjectsQuery;
use App\Shared\Domain\ValueObject\Http\HttpStatusCode;
use App\Shared\Utils;
use App\UI\Controller\BaseController;
use App\UI\Request\Projects\GetProjectsRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GetProjectsController extends BaseController
{
    public function __invoke(GetProjectsRequest $request): JsonResponse
    {
        $pageSize = $request->get('pageSize')
            ? intval($request->get('pageSize'))
            : null;
        $maxUpdatedAt = $request->get('maxUpdatedAt')
            ? Utils::stringToDate($request->get('maxUpdatedAt'))
            : null;

        $response = $this->ask(
            new GetProjectsQuery(
                pageSize: $pageSize,
                maxUpdatedAt: $maxUpdatedAt
            )
        );

        return new JsonResponse($response->data(), HttpStatusCode::HTTP_OK->value);
    }
}
