<?php

declare(strict_types=1);

namespace App\Tests\Builder\Shared\Domain\Criteria\Filter;

use App\Shared\Domain\Criteria\Filter\FilterOperatorEnum;
use App\Shared\Domain\Criteria\Filter\SimpleFilter;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class SimpleFilterBuilder implements BuilderInterface
{
    private function __construct(
        private string $field,
        private mixed $value,
        private FilterOperatorEnum $operator
    ) {
    }

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
            operator: FilterOperatorEnum::from(
                FakeValueGenerator::randomElement(
                    FilterOperatorEnum::values()
                )
            ),
        );
    }

    public function build(): SimpleFilter
    {
        return new SimpleFilter(
            field: $this->field,
            operator: $this->operator,
            value: $this->value
        );
    }

    /** @return SimpleFilter[] */
    public static function buildMany(?int $numFilters = null): array
    {
        if ($numFilters === null) {
            $numFilters = FakeValueGenerator::integer(1, 10);
        }

        $filters = [];
        for ($i = 0; $i < $numFilters; $i++) {
            $filters[] = self::any()->build();
        }

        return $filters;
    }
}
