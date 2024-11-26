<?php

declare(strict_types=1);

namespace App\Tests\Builder\Shared\Domain\Criteria;

use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Filter\Filters;
use App\Shared\Domain\Criteria\Order\OrderBy;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Builder\Shared\Domain\Criteria\Filter\FiltersBuilder;
use App\Tests\Builder\Shared\Domain\Criteria\Order\OrderByBuilder;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class CriteriaBuilder implements BuilderInterface
{
    private function __construct(
        private ?Filters $filters,
        private ?OrderBy $orderBy,
        private ?int $limit,
        private ?int $offset
    ) {
    }

    /**
     * @throws \ValueError
     * @throws \TypeError
     */
    public static function any(): self
    {
        return new self(
            filters: FakeValueGenerator::randomElement([
                null,
                FiltersBuilder::any()->build()
            ]),
            orderBy: FakeValueGenerator::randomElement([
                null,
                OrderByBuilder::any()->build()
            ]),
            limit: FakeValueGenerator::randomElement([
                null,
                FakeValueGenerator::integer()
            ]),
            offset: FakeValueGenerator::randomElement([
                null,
                FakeValueGenerator::integer()
            ])
        );
    }

    public function build(): Criteria
    {
        return new Criteria(
            filters: $this->filters,
            orderBy: $this->orderBy,
            limit: $this->limit,
            offset: $this->offset
        );
    }
}
