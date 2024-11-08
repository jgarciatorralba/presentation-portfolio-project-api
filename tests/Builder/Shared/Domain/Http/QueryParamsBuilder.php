<?php

declare(strict_types=1);

namespace App\Tests\Builder\Shared\Domain\Http;

use App\Shared\Domain\Http\QueryParam;
use App\Shared\Domain\Http\QueryParams;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class QueryParamsBuilder implements BuilderInterface
{
    private const int MIN_PARAMS = 1;
    private const int MAX_PARAMS = 10;

    /** @var QueryParam[] $params */
    private array $params;

    private function __construct(
        QueryParam ...$params
    ) {
        $this->params = $params;
    }

    public static function any(): self
    {
        return new self(
            ...self::randomParams()
        );
    }

    public function build(): QueryParams
    {
        return new QueryParams(
            ...$this->params,
        );
    }

    /** @return QueryParam[] */
    private static function randomParams(?int $numParams = null): array
    {
        if ($numParams === null) {
            $numParams = FakeValueGenerator::integer(
                self::MIN_PARAMS,
                self::MAX_PARAMS
            );
        }

        $params = [];

        $i = 0;
        while ($i < $numParams) {
            $param = QueryParamBuilder::any()->build();
            if (!in_array($param->field(), array_keys($params))) {
                $params[$param->field()] = $param;
                $i++;
            }
        }

        return array_values($params);
    }
}
