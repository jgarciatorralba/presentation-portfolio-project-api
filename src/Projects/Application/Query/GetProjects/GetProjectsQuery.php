<?php

declare(strict_types=1);

namespace App\Projects\Application\Query\GetProjects;

use App\Shared\Domain\Bus\Query\Query;

final class GetProjectsQuery implements Query
{
    public function __construct(
        private readonly ?int $pageSize,
        private readonly ?\DateTimeImmutable $maxCreatedAt
    ) {
    }

    public function pageSize(): ?int
    {
        return $this->pageSize;
    }

    public function maxCreatedAt(): ?\DateTimeImmutable
    {
        return $this->maxCreatedAt;
    }
}
