<?php

declare(strict_types=1);

namespace App\Tests\Builder\Shared\Domain\Criteria\Filter;

use App\Shared\Domain\Criteria\Filter\CompositeFilter;
use App\Shared\Domain\Criteria\Filter\FilterCondition;
use App\Shared\Domain\Criteria\Filter\SimpleFilter;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class CompositeFilterBuilder implements BuilderInterface
{
    /**
     * @param SimpleFilter[] $filters
     */
    private function __construct(
        private array $filters,
        private FilterCondition $condition
    ) {
    }

    public static function any(): self
    {
        return new self(
            filters: SimpleFilterBuilder::buildMany(),
            condition: FilterCondition::from(
                FakeValueGenerator::randomElement(
                    FilterCondition::values()
                )
            ),
        );
    }

    public function build(): CompositeFilter
    {
        return new CompositeFilter(
            filters: $this->filters,
            condition: $this->condition
        );
    }
}
