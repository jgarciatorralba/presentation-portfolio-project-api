<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria\Filter;

final readonly class Filters
{
    /** @var list<Filter> */
    private array $filterGroup;

    public function __construct(
        private FilterCondition $condition = FilterCondition::AND,
        Filter ...$filterGroup
    ) {
        $this->filterGroup = $filterGroup;
    }

    public function condition(): FilterCondition
    {
        return $this->condition;
    }

    /** @return list<Filter> */
    public function filterGroup(): array
    {
        return $this->filterGroup;
    }
}
