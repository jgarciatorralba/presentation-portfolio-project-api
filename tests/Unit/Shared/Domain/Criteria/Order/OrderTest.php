<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria\Order;

use PHPUnit\Framework\TestCase;
use App\Shared\Domain\Criteria\Order\Order;
use App\Tests\Builder\Shared\Domain\Criteria\Order\OrderBuilder;

class OrderTest extends TestCase
{
    public function testOrderIsCreated(): void
    {
        $expected = OrderBuilder::any()->build();

        $actual = new Order(
            orderBy: $expected->orderBy(),
            orderType: $expected->orderType()
        );

        $this->assertEquals($expected, $actual);
    }

    public function testOrderIsCreatedFromValues(): void
    {
        $expected = OrderBuilder::any()->build();

        $actual = Order::fromValues(
            $expected->orderBy(),
            $expected->orderType()->value
        );

        $this->assertEquals($expected, $actual);
    }
}
