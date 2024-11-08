<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Http;

use App\Shared\Domain\Http\QueryParam;
use App\Shared\Domain\Http\QueryParams;
use App\Tests\Builder\Shared\Domain\Http\QueryParamsBuilder;
use PHPUnit\Framework\TestCase;

final class QueryParamsTest extends TestCase
{
    public function testTheyAreCreated(): void
    {
        $expected = QueryParamsBuilder::any()->build();

        $reflection = new \ReflectionClass($expected);
        $params = $reflection->getProperty('params');

        $actual = new QueryParams(
            ...$params->getValue($expected)
        );

        $this->assertInstanceOf(QueryParams::class, $actual);
        $this->assertNotEmpty($params->getValue($actual));
        $this->assertEquals($expected, $actual);
    }

    public function testTheyConvertToString(): void
    {
        $queryParams = QueryParamsBuilder::any()->build();

        $reflection = new \ReflectionClass($queryParams);
        $params = $reflection->getProperty('params');

        $this->assertStringContainsString('=', (string) $queryParams);
        if (count($params->getValue($queryParams)) > 1) {
            $this->assertStringContainsString('&', (string) $queryParams);
        }

        $hasArrayParam = false;
        foreach ($params->getValue($queryParams) as $param) {
            if (is_array($param->value())) {
                $hasArrayParam = true;
                break;
            }
        }

        if ($hasArrayParam) {
            $this->assertStringContainsString('[]=', (string) $queryParams);
        }
    }

    public function testTheyAreMappable(): void
    {
        $queryParams = QueryParamsBuilder::any()->build();

        $reflection = new \ReflectionClass($queryParams);
        $params = $reflection->getProperty('params');
        $paramFields = array_map(
            fn (QueryParam $param): string => $param->field(),
            $params->getValue($queryParams)
        );
        $paramValues = array_map(
            fn (QueryParam $param): string|array => $param->value(),
            $params->getValue($queryParams)
        );

        $this->assertIsArray($queryParams->toArray());
        $this->assertCount(count($params->getValue($queryParams)), $queryParams->toArray());
        $this->assertEquals($paramFields, array_keys($queryParams->toArray()));
        $this->assertEquals($paramValues, array_values($queryParams->toArray()));
    }
}
