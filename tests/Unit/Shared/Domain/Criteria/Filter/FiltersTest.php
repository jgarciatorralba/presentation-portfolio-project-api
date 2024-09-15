<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria\Filter;

use App\Shared\Domain\Criteria\Filter\Filters;
use App\Tests\Builder\Shared\Domain\Criteria\Filter\FiltersBuilder;
use PHPUnit\Framework\TestCase;

class FiltersTest extends TestCase
{
    public function testFiltersAreCreated(): void
    {
        $filtersCreated = FiltersBuilder::any()->build();

        $filtersAsserted = new Filters(
            filters: $filtersCreated->plainFilters(),
            condition: $filtersCreated->condition()
        );

        $this->assertEquals($filtersCreated, $filtersAsserted);
    }
}
