<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

use App\Shared\Domain\Criteria\Filter\Filters;
use App\Shared\Domain\Criteria\Order\Order;

class Criteria
{
    private const MAX_PAGE_SIZE = 50;

    private readonly int $limit;

    /** @param Order[] $orderBy */
    public function __construct(
        private readonly ?Filters $filters = null,
        private readonly ?array $orderBy = null,
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

    /** @return Order[] */
    public function orderBy(): ?array
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
        return !is_null($this->filters()) && !empty($this->filters()->plainFilters());
    }

    public function hasOrder(): bool
    {
        return !empty($this->orderBy());
    }
}
