<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Http;

use App\Shared\Domain\Http\QueryParam;
use App\Tests\Builder\Shared\Domain\Http\QueryParamBuilder;
use PHPUnit\Framework\TestCase;

final class QueryParamTest extends TestCase
{
    public function testItIsCreated(): void
    {
        $expected = QueryParamBuilder::any()->build();

        $actual = new QueryParam(
            field: $expected->field(),
            value: $expected->value()
        );

        self::assertEquals($expected, $actual);
    }

    public function testItConvertsToString(): void
    {
        $param = QueryParamBuilder::any()->build();

        if (is_array($param->value())) {
            $expected = '';
            foreach ($param->value() as $key => $value) {
                if ($key > 0) {
                    $expected .= '&';
                }
                $expected .= urlencode($param->field()) . '[]=' . urlencode($value);
            }
        } else {
            $expected = urlencode($param->field()) . '=' . urlencode($param->value());
        }

        $actual = (string) $param;

        self::assertEquals($expected, $actual);
    }
}
