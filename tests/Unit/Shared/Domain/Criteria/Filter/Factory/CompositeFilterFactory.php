<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria\Filter\Factory;

use App\Shared\Domain\Criteria\Filter\SimpleFilter;
use App\Shared\Domain\Criteria\Filter\CompositeFilter;
use App\Shared\Domain\Criteria\Filter\FilterConditionEnum;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class CompositeFilterFactory
{
    /** @param SimpleFilter[] $filters */
    public static function create(
        array $filters = null,
        FilterConditionEnum $condition = null
    ): CompositeFilter {
        return new CompositeFilter(
            filters: $filters ?? SimpleFilterFactory::createMany(),
            condition: $condition ?? FilterConditionEnum::from(
                FakeValueGenerator::randomElement(FilterConditionEnum::values())
            )
        );
    }
}
