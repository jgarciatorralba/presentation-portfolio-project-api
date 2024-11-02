<?php

declare(strict_types=1);

namespace App\UI\Controller\Projects;

use App\Projects\Application\Command\CreateProject\CreateProjectCommand;
use App\Shared\Domain\Http\HttpStatusCode;
use App\UI\Controller\BaseController;
use App\UI\Request\Projects\CreateProjectRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CreateProjectController extends BaseController
{
    public function __invoke(CreateProjectRequest $request): JsonResponse
    {
        $data = $request->payload();

        $this->dispatch(
            new CreateProjectCommand(
                id: $data['id'],
                name: $data['name'],
                description: $data['description'] ?? null,
                topics: $data['topics'] ?? null,
                repository: $data['repository'],
                homepage: $data['homepage'] ?? null,
                archived: $data['archived'],
                lastPushedAt: new \DateTimeImmutable($data['lastPushedAt'])
            )
        );

        return new JsonResponse(null, HttpStatusCode::HTTP_CREATED->value, [
            'Location' => $this->getResourceUrl('projects', $data['id'])
        ]);
    }
}
