<?php

declare(strict_types=1);

namespace App\UI\Controller\Projects;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateProjectController
{
    public function __invoke(): Response
    {
        return new JsonResponse([
            'id' => 123
        ], Response::HTTP_CREATED);
    }
}
