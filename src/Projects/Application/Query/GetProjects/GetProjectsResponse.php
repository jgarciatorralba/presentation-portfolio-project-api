<?php

declare(strict_types=1);

namespace App\Projects\Application\Query\GetProjects;

use App\Shared\Domain\Bus\Query\Response;

final class GetProjectsResponse implements Response
{
    public function __construct(
        /** @var array <mixed> */
        private readonly array $data
    ) {
    }

    public function data(): array
    {
        return $this->data;
    }
}
