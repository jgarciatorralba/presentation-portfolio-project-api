<?php

declare(strict_types=1);

namespace App\Projects\Application\Query\GetProjects;

use App\Shared\Domain\Bus\Query\Query;

final readonly class GetProjectsQuery implements Query
{
    public function __construct(
        private ?int $pageSize = null,
        private ?\DateTimeImmutable $maxUpdatedAt = null
    ) {
    }

    public function pageSize(): ?int
    {
        return $this->pageSize;
    }

    public function maxUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->maxUpdatedAt;
    }
}
