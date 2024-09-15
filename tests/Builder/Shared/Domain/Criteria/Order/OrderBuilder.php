<?php

declare(strict_types=1);

namespace App\Tests\Builder\Shared\Domain\Criteria\Order;

use App\Shared\Domain\Criteria\Order\Order;
use App\Shared\Domain\Criteria\Order\OrderEnum;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class OrderBuilder implements BuilderInterface
{
    private function __construct(
        private string $orderBy,
        private OrderEnum $orderType
    ) {
    }

    public static function any(): self
    {
        return new self(
            orderBy: FakeValueGenerator::text(),
            orderType: OrderEnum::from(
                FakeValueGenerator::randomElement(OrderEnum::values())
            )
        );
    }

    public function build(): Order
    {
        return new Order(
            orderBy: $this->orderBy,
            orderType: $this->orderType
        );
    }

    /**
     * @return Order[]
     */
    public static function buildMany(?int $numOrders = null): array
    {
        if ($numOrders === null) {
            $numOrders = FakeValueGenerator::integer(1, 10);
        }

        $orders = [];
        for ($i = 0; $i < $numOrders; $i++) {
            $orders[] = self::any()->build();
        }

        return $orders;
    }
}
