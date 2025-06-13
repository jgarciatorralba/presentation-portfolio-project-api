<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria\Order;

final readonly class OrderBy
{
    /** @var list<Order> */
    private array $orderings;

    public function __construct(Order ...$orderings)
    {
        $this->orderings = array_values($orderings);
    }

    /** @return list<Order> */
    public function orderings(): array
    {
        return $this->orderings;
    }
}
