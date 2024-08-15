<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria\Filter\Factory;

use App\Shared\Domain\Criteria\Filter\SimpleFilter;
use App\Shared\Domain\Criteria\Filter\CompositeFilter;
use App\Shared\Domain\Criteria\Filter\FilterConditionEnum;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;

final class CompositeFilterFactory
{
    /** @param SimpleFilter[] $filters */
    public static function create(
        array $filters = null,
        FilterConditionEnum $condition = null
    ): CompositeFilter {
        return new CompositeFilter(
            filters: $filters ?? self::generateFilters(),
            condition: $condition ?? FilterConditionEnum::from(
                FakeValueGenerator::randomElement(FilterConditionEnum::values())
            )
        );
    }

    /** @return SimpleFilter[] */
    private static function generateFilters(): array
    {
        $filters = [];
        $filtersCount = FakeValueGenerator::integer(1, 10);

        for ($i = 0; $i < $filtersCount; $i++) {
            $filters[] = SimpleFilterFactory::create();
        }

        return $filters;
    }
}
