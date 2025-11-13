<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\Criteria\Filter;

use App\Shared\Domain\Criteria\Filter\Filters;
use Tests\Support\Builder\Shared\Domain\Criteria\Filter\FiltersBuilder;
use PHPUnit\Framework\TestCase;

class FiltersTest extends TestCase
{
    public function testTheyAreCreated(): void
    {
        $expected = FiltersBuilder::any()->build();

        $actual = new Filters(
            $expected->condition(),
            ...$expected->filterGroup()
        );

        $this->assertEquals($expected, $actual);
    }
}
