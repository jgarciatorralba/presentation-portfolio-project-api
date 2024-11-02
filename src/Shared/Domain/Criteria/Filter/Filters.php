<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria\Filter;

final readonly class Filters
{
    /** @param Filter[] $filters */
    public function __construct(
        private array $filters = [],
        private FilterCondition $condition = FilterCondition::AND
    ) {
    }

    /** @return Filter[] */
    public function plainFilters(): array
    {
        return $this->filters;
    }

    public function condition(): FilterCondition
    {
        return $this->condition;
    }
}
