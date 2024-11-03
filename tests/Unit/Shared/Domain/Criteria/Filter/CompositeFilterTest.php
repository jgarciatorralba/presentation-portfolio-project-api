<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria\Filter;

use App\Shared\Domain\Criteria\Filter\CompositeFilter;
use App\Tests\Builder\Shared\Domain\Criteria\Filter\CompositeFilterBuilder;
use PHPUnit\Framework\TestCase;

class CompositeFilterTest extends TestCase
{
    public function testCompositeFilterIsCreated(): void
    {
        $expected = CompositeFilterBuilder::any()->build();

        $actual = new CompositeFilter(
            $expected->condition(),
            ...$expected->filters(),
        );

        $this->assertEquals($expected, $actual);
    }
}
