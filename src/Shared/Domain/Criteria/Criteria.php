<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

use App\Shared\Domain\Criteria\Filter\Filters;
use App\Shared\Domain\Criteria\Order\OrderBy;

class Criteria
{
    private const MAX_PAGE_SIZE = 50;

    private readonly int $limit;

    public function __construct(
        private readonly ?Filters $filters = null,
        private readonly ?OrderBy $orderBy = null,
        ?int $limit = null,
        private readonly ?int $offset = null
    ) {
        $this->limit = ($limit === null || $limit > self::MAX_PAGE_SIZE)
            ? self::MAX_PAGE_SIZE
            : $limit;
    }

    public function filters(): ?Filters
    {
        return $this->filters;
    }

    public function orderBy(): ?OrderBy
    {
        return $this->orderBy;
    }

    public function limit(): ?int
    {
        return $this->limit;
    }

    public function offset(): ?int
    {
        return $this->offset;
    }

    public function hasFilters(): bool
    {
        return !is_null($this->filters())
            && !empty($this->filters()->filterGroup());
    }

    public function hasOrder(): bool
    {
        return !is_null($this->orderBy())
            && !empty($this->orderBy()->orderings());
    }
}
