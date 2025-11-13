<?php

declare(strict_types=1);

namespace Tests\Support\Builder\Shared\Domain\Http;

use App\Shared\Domain\Http\QueryParam;
use Tests\Support\Builder\BuilderInterface;
use Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class QueryParamBuilder implements BuilderInterface
{
    private const int MIN_VALUES = 1;
    private const int MAX_VALUES = 10;

    /** @param string|string[] $value */
    public function __construct(
        private string $field,
        private string|array $value
    ) {
    }

    public static function any(): self
    {
        return new self(
            field: FakeValueGenerator::string(),
            value: self::randomValues()
        );
    }

    public function build(): QueryParam
    {
        return new QueryParam(
            field: $this->field,
            value: $this->value
        );
    }

    /** @return string|string[] */
    private static function randomValues(?int $numValues = null): string|array
    {
        if ($numValues === null) {
            $numValues = FakeValueGenerator::integer(
                min: self::MIN_VALUES,
                max: self::MAX_VALUES
            );
        }

        $values = [];
        for ($i = 0; $i < $numValues; $i++) {
            $values[] = FakeValueGenerator::string();
        }

        return FakeValueGenerator::randomElement([
            FakeValueGenerator::string(),
            $values
        ]);
    }
}
