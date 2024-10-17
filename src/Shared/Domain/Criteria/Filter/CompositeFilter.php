<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria\Filter;

final readonly class CompositeFilter implements Filter
{
    /** @param SimpleFilter[] $filters */
    public function __construct(
        private array $filters = [],
        private FilterConditionEnum $condition = FilterConditionEnum::AND
    ) {
    }

    /** @return SimpleFilter[] */
    public function filters(): array
    {
        return $this->filters;
    }

    public function condition(): FilterConditionEnum
    {
        return $this->condition;
    }
}
