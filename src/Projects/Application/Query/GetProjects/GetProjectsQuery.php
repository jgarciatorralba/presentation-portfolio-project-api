<?php

declare(strict_types=1);

namespace App\Projects\Application\Query\GetProjects;

use App\Shared\Domain\Bus\Query\Query;

final class GetProjectsQuery implements Query
{
    public function __construct(
        private readonly ?int $pageSize = null,
        private readonly ?\DateTimeImmutable $maxCreatedAt = null
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
