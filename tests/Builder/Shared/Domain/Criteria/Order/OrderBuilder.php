<?php

declare(strict_types=1);

namespace App\Tests\Builder\Shared\Domain\Criteria\Order;

use App\Shared\Domain\Criteria\Order\Order;
use App\Shared\Domain\Criteria\Order\OrderType;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class OrderBuilder implements BuilderInterface
{
    private const int MIN_ORDERS = 1;
    private const int MAX_ORDERS = 10;

    private function __construct(
        private string $field,
        private OrderType $type
    ) {
    }

    public static function any(): self
    {
        return new self(
            field: FakeValueGenerator::text(),
            type: OrderType::from(
                FakeValueGenerator::randomElement(OrderType::values())
            )
        );
    }

    public function build(): Order
    {
        return new Order(
            field: $this->field,
            type: $this->type
        );
    }

    /**
     * @return Order[]
     */
    public static function buildMany(?int $numOrders = null): array
    {
        if ($numOrders === null) {
            $numOrders = FakeValueGenerator::integer(
                self::MIN_ORDERS,
                self::MAX_ORDERS
            );
        }

        $orders = [];
        for ($i = 0; $i < $numOrders; $i++) {
            $orders[] = self::any()->build();
        }

        return $orders;
    }
}
