<?php

declare(strict_types=1);

namespace Tests\Support\Builder\Shared\Domain\Criteria\Filter;

use App\Shared\Domain\Criteria\Filter\FilterOperator;
use App\Shared\Domain\Criteria\Filter\SimpleFilter;
use Tests\Support\Builder\BuilderInterface;
use Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class SimpleFilterBuilder implements BuilderInterface
{
    private const int MIN_FILTERS = 1;
    private const int MAX_FILTERS = 10;

    private function __construct(
        private string $field,
        private mixed $value,
        private FilterOperator $operator
    ) {
    }

    /**
     * @throws \ValueError
     * @throws \TypeError
     */
    public static function any(): self
    {
        return new self(
            field: FakeValueGenerator::text(),
            value: FakeValueGenerator::randomElement([
                FakeValueGenerator::text(),
                FakeValueGenerator::integer(),
                FakeValueGenerator::float(),
                FakeValueGenerator::boolean(),
            ]),
            operator: FilterOperator::from(
                FakeValueGenerator::randomElement(
                    FilterOperator::values()
                )
            ),
        );
    }

    public function build(): SimpleFilter
    {
        return new SimpleFilter(
            field: $this->field,
            value: $this->value,
            operator: $this->operator
        );
    }

    /**
     * @throws \ValueError
     * @throws \TypeError
     *
     * @return SimpleFilter[]
     */
    public static function buildMany(?int $numFilters = null): array
    {
        if ($numFilters === null) {
            $numFilters = FakeValueGenerator::integer(
                self::MIN_FILTERS,
                self::MAX_FILTERS
            );
        }

        $filters = [];
        for ($i = 0; $i < $numFilters; $i++) {
            $filters[] = self::any()->build();
        }

        return $filters;
    }
}
