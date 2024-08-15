<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria\Filter;

use App\Shared\Domain\Criteria\Filter\CompositeFilter;
use App\Tests\Unit\Shared\Domain\Criteria\Filter\Factory\CompositeFilterFactory;
use PHPUnit\Framework\TestCase;

class CompositeFilterTest extends TestCase
{
    public function testCompositeFilterIsCreated(): void
    {
        $compositeFilterCreated = CompositeFilterFactory::create();

        $compositeFilterAsserted = new CompositeFilter(
            filters: $compositeFilterCreated->filters(),
            condition: $compositeFilterCreated->condition()
        );

        $this->assertEquals($compositeFilterCreated, $compositeFilterAsserted);
    }
}
