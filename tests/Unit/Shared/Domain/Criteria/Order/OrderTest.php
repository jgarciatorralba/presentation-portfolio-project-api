<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria\Order;

use PHPUnit\Framework\TestCase;
use App\Shared\Domain\Criteria\Order\Order;
use App\Tests\Unit\Shared\Domain\Criteria\Order\Factory\OrderFactory;

class OrderTest extends TestCase
{
	public function testOrderIsCreated(): void
	{
		$orderCreated = OrderFactory::create();

		$orderAsserted = new Order(
			orderBy: $orderCreated->orderBy(),
			orderType: $orderCreated->orderType()
		);

		$this->assertEquals($orderCreated, $orderAsserted);
	}

	public function testOrderIsCreatedFromValues(): void
	{
		$orderCreated = OrderFactory::create();

		$orderAsserted = Order::fromValues(
			$orderCreated->orderBy(),
			$orderCreated->orderType()->value
		);

		$this->assertEquals($orderCreated, $orderAsserted);
	}
}
