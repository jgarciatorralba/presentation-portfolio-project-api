<?php

declare(strict_types=1);

namespace App\Projects\Application\Query\GetProjects;

use App\Shared\Domain\Bus\Query\Response;

final readonly class GetProjectsResponse implements Response
{
    /** @param array<mixed> $data */
    public function __construct(
        private array $data
    ) {
    }

    public function data(): array
    {
        return $this->data;
    }
}
