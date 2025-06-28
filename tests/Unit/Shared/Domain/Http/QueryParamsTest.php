<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Http;

use App\Shared\Domain\Http\QueryParam;
use App\Shared\Domain\Http\QueryParams;
use App\Tests\Support\Builder\Shared\Domain\Http\QueryParamsBuilder;
use PHPUnit\Framework\TestCase;

final class QueryParamsTest extends TestCase
{
    private QueryParams $expected;

    /** @var list<QueryParam> $params */
    private array $params;

    protected function setUp(): void
    {
        $this->expected = QueryParamsBuilder::any()->build();

        $reflection = new \ReflectionClass($this->expected);
        $params = $reflection->getProperty('params');

        $this->params = $params->getValue($this->expected);
    }

    public function testTheyAreCreated(): void
    {
        $actual = new QueryParams(...$this->params);

        $this->assertInstanceOf(QueryParams::class, $actual);
        $this->assertEquals($this->expected, $actual);
    }

    public function testTheyConvertToString(): void
    {
        $actual = new QueryParams(...$this->params);

        $this->assertStringContainsString('=', (string) $actual);
        if (count($this->params) > 1) {
            $this->assertStringContainsString('&', (string) $actual);
        }

        $hasArrayParam = false;
        foreach ($this->params as $param) {
            if (is_array($param->value())) {
                $hasArrayParam = true;
                break;
            }
        }

        if ($hasArrayParam) {
            $this->assertStringContainsString('[]=', (string) $actual);
        }
    }

    public function testTheyAreMappable(): void
    {
        $actual = new QueryParams(...$this->params);

        $paramFields = array_map(
            fn (QueryParam $param): string => $param->field(),
            $this->params
        );
        $paramValues = array_map(
            fn (QueryParam $param): string|array => $param->value(),
            $this->params
        );

        $this->assertIsArray($actual->toArray());
        $this->assertCount(count($this->params), $actual->toArray());
        $this->assertEquals($paramFields, array_keys($actual->toArray()));
        $this->assertEquals($paramValues, array_values($actual->toArray()));
    }
}
