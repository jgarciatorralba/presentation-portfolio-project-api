<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria\Order;

final class Order
{
    public function __construct(
        private readonly string $orderBy,
        private readonly OrderEnum $orderType
    ) {
    }

    public static function fromValues(?string $orderBy, ?string $order): ?self
    {
        if ($orderBy === null || $order === null || OrderEnum::tryFrom($order) === null) {
            return null;
        }

        return new self($orderBy, OrderEnum::from($order));
    }

    public function orderBy(): string
    {
        return $this->orderBy;
    }

    public function orderType(): OrderEnum
    {
        return $this->orderType;
    }
}
