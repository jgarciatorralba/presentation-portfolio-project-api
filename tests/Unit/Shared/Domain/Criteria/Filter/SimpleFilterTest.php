<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria\Filter;

use App\Shared\Domain\Criteria\Filter\SimpleFilter;
use App\Tests\Support\Builder\Shared\Domain\Criteria\Filter\SimpleFilterBuilder;
use PHPUnit\Framework\TestCase;

class SimpleFilterTest extends TestCase
{
    public function testItIsCreated(): void
    {
        $simpleFilterCreated = SimpleFilterBuilder::any()->build();

        $simpleFilterAsserted = new SimpleFilter(
            field: $simpleFilterCreated->field(),
            value: $simpleFilterCreated->value(),
            operator: $simpleFilterCreated->operator()
        );

        $this->assertEquals($simpleFilterCreated, $simpleFilterAsserted);
    }
}
