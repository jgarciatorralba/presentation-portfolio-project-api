<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria\Order\Factory;

use App\Shared\Domain\Criteria\Order\Order;
use App\Shared\Domain\Criteria\Order\OrderEnum;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class OrderFactory
{
    public static function create(
        string $orderBy = null,
        OrderEnum $orderType = null
    ): Order {
        return new Order(
            orderBy: $orderBy ?? FakeValueGenerator::text(),
            orderType: $orderType ?? OrderEnum::from(
                FakeValueGenerator::randomElement(OrderEnum::values())
            )
        );
    }

    /** @return Order[] */
    public static function createMany(?int $numOrders = null): array
    {
        if ($numOrders === null) {
            $numOrders = FakeValueGenerator::integer(1, 10);
        }

        $orders = [];
        for ($i = 0; $i < $numOrders; $i++) {
            $orders[] = self::create();
        }

        return $orders;
    }
}
