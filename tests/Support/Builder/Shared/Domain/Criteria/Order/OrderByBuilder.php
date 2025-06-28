<?php

declare(strict_types=1);

namespace App\Tests\Support\Builder\Shared\Domain\Criteria\Order;

use App\Shared\Domain\Criteria\Order\Order;
use App\Shared\Domain\Criteria\Order\OrderBy;
use App\Tests\Support\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class OrderByBuilder implements BuilderInterface
{
    private const int MIN_ORDERINGS = 1;
    private const int MAX_ORDERINGS = 10;

    /** @var Order[] $orderings */
    private array $orderings;

    private function __construct(
        Order ...$orderings
    ) {
        $this->orderings = $orderings;
    }

    /**
     * @throws \ValueError
     * @throws \TypeError
     */
    public static function any(): self
    {
        return new self(
            ...self::randomOrderings()
        );
    }

    public function build(): OrderBy
    {
        return new OrderBy(
            ...$this->orderings,
        );
    }

    /**
     * @throws \ValueError
     * @throws \TypeError
     *
     * @return Order[]
     */
    public static function randomOrderings(?int $numOrders = null): array
    {
        if ($numOrders === null) {
            $numOrders = FakeValueGenerator::integer(
                self::MIN_ORDERINGS,
                self::MAX_ORDERINGS
            );
        }

        $orderings = [];
        for ($i = 0; $i < $numOrders; $i++) {
            $orderings[] = OrderBuilder::any()->build();
        }

        return $orderings;
    }
}
