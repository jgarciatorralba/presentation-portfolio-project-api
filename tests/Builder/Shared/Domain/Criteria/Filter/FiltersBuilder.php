<?php

declare(strict_types=1);

namespace App\Tests\Builder\Shared\Domain\Criteria\Filter;

use App\Shared\Domain\Criteria\Filter\Filter;
use App\Shared\Domain\Criteria\Filter\FilterCondition;
use App\Shared\Domain\Criteria\Filter\Filters;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class FiltersBuilder implements BuilderInterface
{
    /**
     * @param Filter[] $filters
     */
    private function __construct(
        private array $filters,
        private FilterCondition $condition
    ) {
    }

    public static function any(): self
    {
        return new self(
            filters: self::randomFilters(),
            condition: FilterCondition::from(
                FakeValueGenerator::randomElement(
                    FilterCondition::values()
                )
            )
        );
    }

    public function build(): Filters
    {
        return new Filters(
            filters: $this->filters,
            condition: $this->condition
        );
    }

    /** @return Filter[] */
    private static function randomFilters(?int $numFilters = null): array
    {
        if ($numFilters === null) {
            $numFilters = FakeValueGenerator::integer(1, 10);
        }

        $filters = [];
        for ($i = 0; $i < $numFilters; $i++) {
            $filters[] = FakeValueGenerator::randomElement([
                SimpleFilterBuilder::any()->build(),
                CompositeFilterBuilder::any()->build(),
            ]);
        }

        return $filters;
    }
}
