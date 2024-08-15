<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria\Filter\Factory;

use App\Shared\Domain\Criteria\Filter\FilterOperatorEnum;
use App\Shared\Domain\Criteria\Filter\SimpleFilter;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;

final class SimpleFilterFactory
{
	public static function create(
		string $field = null,
		mixed $value = null,
		FilterOperatorEnum $operator = null
	): SimpleFilter
	{
		return new SimpleFilter(
			field: $field ?? FakeValueGenerator::text(),
			value: $value ?? FakeValueGenerator::randomElement([
				FakeValueGenerator::text(),
				FakeValueGenerator::integer(),
				FakeValueGenerator::float(),
				FakeValueGenerator::boolean(),
			]),
			operator: $operator ?? FilterOperatorEnum::from(
				FakeValueGenerator::randomElement(FilterOperatorEnum::values())
			),
		);
	}
}
