<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria\Order;

final readonly class Order
{
    public function __construct(
        private string $orderBy,
        private OrderType $orderType
    ) {
    }

    public static function fromValues(?string $orderBy, ?string $order): ?self
    {
        if ($orderBy === null || $order === null || OrderType::tryFrom($order) === null) {
            return null;
        }

        return new self($orderBy, OrderType::from($order));
    }

    public function orderBy(): string
    {
        return $this->orderBy;
    }

    public function orderType(): OrderType
    {
        return $this->orderType;
    }
}
