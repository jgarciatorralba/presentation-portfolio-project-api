<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria\Filter;

use App\Shared\Domain\Criteria\Filter\Filters;
use App\Tests\Unit\Shared\Domain\Criteria\Filter\Factory\FiltersFactory;
use PHPUnit\Framework\TestCase;

class FiltersTest extends TestCase
{
    public function testFiltersAreCreated(): void
    {
        $filtersCreated = FiltersFactory::create();

        $filtersAsserted = new Filters(
            filters: $filtersCreated->plainFilters(),
            condition: $filtersCreated->condition()
        );

        $this->assertEquals($filtersCreated, $filtersAsserted);
    }
}
