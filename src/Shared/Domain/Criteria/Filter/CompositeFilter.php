<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria\Filter;

final readonly class CompositeFilter implements Filter
{
    /** @var list<SimpleFilter> */
    private array $filters;

    public function __construct(
        private FilterCondition $condition = FilterCondition::AND,
        SimpleFilter ...$filters
    ) {
        $this->filters = $filters;
    }

    public function condition(): FilterCondition
    {
        return $this->condition;
    }

    /** @return SimpleFilter[] */
    public function filters(): array
    {
        return $this->filters;
    }
}
