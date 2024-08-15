<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria\Factory;

use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Filter\Filters;
use App\Shared\Domain\Criteria\Order\Order;
use App\Tests\Unit\Shared\Domain\Criteria\Filter\Factory\FiltersFactory;
use App\Tests\Unit\Shared\Domain\Criteria\Order\Factory\OrderFactory;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;

final class CriteriaFactory
{
	/** @param Order[] $orderBy */
	public static function create(
		?Filters $filters = null,
		?array $orderBy = null,
		?int $limit = null,
		?int $offset = null
	): Criteria {
		return new Criteria(
			filters: $filters ?? FakeValueGenerator::randomElement([null, FiltersFactory::create()]),
			orderBy: $orderBy ?? FakeValueGenerator::randomElement([null, self::generatedOrderBy()]),
			limit: $limit ?? FakeValueGenerator::randomElement([null, FakeValueGenerator::integer()]),
			offset: $offset ?? FakeValueGenerator::randomElement([null, FakeValueGenerator::integer()])
		);
	}

	/** @return Order[] */
	private static function generatedOrderBy(): array
	{
		$orderBy = [];
		$orderByCount = FakeValueGenerator::integer(1, 10);

		for ($i = 0; $i < $orderByCount; $i++) {
			$orderBy[] = OrderFactory::create();
		}

		return $orderBy;
	}
}
