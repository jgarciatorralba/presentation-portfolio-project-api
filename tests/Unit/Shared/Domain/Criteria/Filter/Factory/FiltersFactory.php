<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria\Filter\Factory;

use App\Shared\Domain\Criteria\Filter\Filter;
use App\Shared\Domain\Criteria\Filter\FilterConditionEnum;
use App\Shared\Domain\Criteria\Filter\Filters;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class FiltersFactory
{
    /** @param Filter[] $filters */
    public static function create(
        array $filters = null,
        FilterConditionEnum $condition = null
    ): Filters {
        return new Filters(
            filters: $filters ?? self::generateRandomFilters(),
            condition: $condition ?? FilterConditionEnum::from(
                FakeValueGenerator::randomElement(FilterConditionEnum::values())
            )
        );
    }

    /** @return Filter[] */
    private static function generateRandomFilters(?int $numFilters = null): array
    {
        if ($numFilters === null) {
            $numFilters = FakeValueGenerator::integer(1, 10);
        }

        $filters = [];
        for ($i = 0; $i < $numFilters; $i++) {
            $filters[] = FakeValueGenerator::randomElement([
                SimpleFilterFactory::create(),
                CompositeFilterFactory::create(),
            ]);
        }

        return $filters;
    }
}
