<?php

declare(strict_types=1);

namespace App\UI\Controller\Projects;

use App\Projects\Application\Command\CreateProject\CreateProjectCommand;
use App\UI\Controller\BaseController;
use App\UI\Request\Projects\CreateProjectRequest;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateProjectController extends BaseController
{
    public function __invoke(CreateProjectRequest $request): Response
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
                lastPushedAt: $data['lastPushedAt'],
                createdAt: new DateTimeImmutable()
            )
        );

        return new JsonResponse([
            'id' => $data['id']
        ], Response::HTTP_CREATED);
    }
}
