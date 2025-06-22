<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria\Order;

use PHPUnit\Framework\TestCase;
use App\Shared\Domain\Criteria\Order\Order;
use App\Tests\Builder\Shared\Domain\Criteria\Order\OrderBuilder;

class OrderTest extends TestCase
{
    public function testItIsCreated(): void
    {
        $expected = OrderBuilder::any()->build();

        $actual = new Order(
            field: $expected->field(),
            type: $expected->type()
        );

        $this->assertEquals($expected, $actual);
    }

    public function testItIsCreatedFromValidValues(): void
    {
        $expected = OrderBuilder::any()->build();

        $actual = Order::fromValues(
            $expected->field(),
            $expected->type()->value
        );

        $this->assertEquals($expected, $actual);
    }

	public function testItIsNotCreatedFromNullValues(): void
	{
		$actual = Order::fromValues(null, null);

		$this->assertNull($actual);
	}
}
