<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria\Order;

use App\Shared\Domain\Criteria\Order\OrderBy;
use App\Tests\Support\Builder\Shared\Domain\Criteria\Order\OrderByBuilder;
use PHPUnit\Framework\TestCase;

final class OrderByTest extends TestCase
{
    public function testItIsCreated(): void
    {
        $expected = OrderByBuilder::any()->build();

        $actual = new OrderBy(...$expected->orderings());

        $this->assertEquals($expected, $actual);
    }
}
