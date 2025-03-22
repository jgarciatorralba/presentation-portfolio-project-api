<?php

declare(strict_types=1);

namespace App\Projects\Application\Query\GetProjects;

use App\Shared\Domain\Bus\Query\Query;

final readonly class GetProjectsQuery implements Query
{
    private const int MAX_PAGE_SIZE = 50;

    private int $pageSize;

    public function __construct(
        ?int $pageSize = null,
        private ?\DateTimeImmutable $maxPushedAt = null
    ) {
        $this->pageSize = ($pageSize === null || $pageSize > self::MAX_PAGE_SIZE)
            ? self::MAX_PAGE_SIZE
            : $pageSize;
    }

    public function pageSize(): int
    {
        return $this->pageSize;
    }

    public function maxPushedAt(): ?\DateTimeImmutable
    {
        return $this->maxPushedAt;
    }
}
