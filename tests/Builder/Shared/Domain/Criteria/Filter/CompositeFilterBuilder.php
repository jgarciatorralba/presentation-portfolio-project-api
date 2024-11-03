<?php

declare(strict_types=1);

namespace App\Tests\Builder\Shared\Domain\Criteria\Filter;

use App\Shared\Domain\Criteria\Filter\CompositeFilter;
use App\Shared\Domain\Criteria\Filter\FilterCondition;
use App\Shared\Domain\Criteria\Filter\SimpleFilter;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class CompositeFilterBuilder implements BuilderInterface
{
    /** @var SimpleFilter[] $filters */
    private array $filters;

    private function __construct(
        private FilterCondition $condition,
        SimpleFilter ...$filters
    ) {
        $this->filters = $filters;
    }

    public static function any(): self
    {
        return new self(
            FilterCondition::from(
                FakeValueGenerator::randomElement(
                    FilterCondition::values()
                )
            ),
            ...SimpleFilterBuilder::buildMany(),
        );
    }

    public function build(): CompositeFilter
    {
        return new CompositeFilter(
            $this->condition,
            ...$this->filters,
        );
    }
}
