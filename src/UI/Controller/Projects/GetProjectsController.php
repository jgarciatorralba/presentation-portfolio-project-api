<?php

declare(strict_types=1);

namespace App\UI\Controller\Projects;

use App\Projects\Application\Query\GetProjects\GetProjectsQuery;
use App\Projects\Application\Query\GetProjects\GetProjectsResponse;
use App\Shared\Domain\Http\HttpStatusCode;
use App\Shared\Utils;
use App\UI\Controller\BaseController;
use App\UI\Request\Projects\GetProjectsRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class GetProjectsController extends BaseController
{
    /** @throws \InvalidArgumentException */
    public function __invoke(GetProjectsRequest $request): JsonResponse
    {
        $pageSize = $request->getQueryParam('pageSize')
            ? intval($request->getQueryParam('pageSize'))
            : null;
        $maxPushedAt = $request->getQueryParam('maxPushedAt')
            ? Utils::stringToDate($request->getQueryParam('maxPushedAt'))
            : null;

        $response = $this->ask(
            new GetProjectsQuery(
                pageSize: $pageSize,
                maxPushedAt: $maxPushedAt
            )
        );

        return new JsonResponse(
            data: $response->data(),
            status: HttpStatusCode::HTTP_OK->value,
            headers: [
                'Access-Control-Expose-Headers' => 'Next',
                'Content-Type' => 'application/json',
                'Next' => $response instanceof GetProjectsResponse && $response->totalCount() > $response->count()
            ]
        );
    }
}
