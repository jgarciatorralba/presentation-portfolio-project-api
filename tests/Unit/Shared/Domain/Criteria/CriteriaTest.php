<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\Criteria;

use App\Shared\Domain\Criteria\Criteria;
use Tests\Support\Builder\Shared\Domain\Criteria\CriteriaBuilder;
use PHPUnit\Framework\TestCase;

class CriteriaTest extends TestCase
{
    public function testItIsCreated(): void
    {
        $expected = CriteriaBuilder::any()->build();

        $actual = new Criteria(
            filters: $expected->filters(),
            orderBy: $expected->orderBy(),
            limit: $expected->limit(),
            offset: $expected->offset()
        );

        $this->assertEquals($expected, $actual);
    }
}
