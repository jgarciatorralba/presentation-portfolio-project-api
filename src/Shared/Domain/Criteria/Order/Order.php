<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria\Order;

final readonly class Order
{
    public function __construct(
        private string $field,
        private OrderType $type
    ) {
    }

    /**
     * @throws \ValueError
     * @throws \TypeError
     */
    public static function fromValues(?string $field, ?string $type): ?self
    {
        if ($field === null || $type === null || OrderType::tryFrom($type) === null) {
            return null;
        }

        return new self($field, OrderType::from($type));
    }

    public function field(): string
    {
        return $this->field;
    }

    public function type(): OrderType
    {
        return $this->type;
    }
}
