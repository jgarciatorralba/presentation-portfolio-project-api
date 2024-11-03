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
    private const int MIN_FILTERS = 1;
    private const int MAX_FILTERS = 10;

    /** @var Filter[] $filters */
    private array $filters;

    private function __construct(
        private FilterCondition $condition,
        Filter ...$filters,
    ) {
        $this->filters = $filters;
    }

    public static function any(): self
    {
        return new self(
            FilterCondition::from(
                FakeValueGenerator::randomElement(
                    FilterCondition::values()
                )
            ),
            ...self::randomFilters(),
        );
    }

    public function build(): Filters
    {
        return new Filters(
            $this->condition,
            ...$this->filters,
        );
    }

    /** @return Filter[] */
    private static function randomFilters(?int $numFilters = null): array
    {
        if ($numFilters === null) {
            $numFilters = FakeValueGenerator::integer(
                self::MIN_FILTERS,
                self::MAX_FILTERS
            );
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
