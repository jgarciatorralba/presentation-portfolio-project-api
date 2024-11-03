<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Http;

use App\Shared\Domain\Http\HttpHeader;
use App\Shared\Domain\Http\HttpHeaders;
use App\Tests\Builder\Shared\Domain\Http\HttpHeaderBuilder;
use App\Tests\Builder\Shared\Domain\Http\HttpHeadersBuilder;
use PHPUnit\Framework\TestCase;

final class HttpHeadersTest extends TestCase
{
    public function testTheyAreCreated(): void
    {
        $headers = HttpHeadersBuilder::any()->build();

        $this->assertInstanceOf(HttpHeaders::class, $headers);
        $this->assertNotEmpty($headers->all());
    }

    public function testTheyAreMergedWhenNameIsTheSame(): void
    {
        $firstHeader = HttpHeaderBuilder::any()
            ->withName('a-valid-header')
            ->build();

        $secondHeader = HttpHeaderBuilder::any()
        ->withName('A-Valid-Header')
        ->build();

        $headers = new HttpHeaders($firstHeader, $secondHeader);

        $this->assertCount(1, $headers->all());
        $this->assertEquals(
            count($firstHeader->values()) + count($secondHeader->values()),
            count($headers->get($firstHeader->name())->values())
        );
    }

    public function testHeadersCanBeRetrieved(): void
    {
        $headers = HttpHeadersBuilder::any()->build();

        $this->assertNotEmpty($headers->all());
        $this->assertContainsOnlyInstancesOf(HttpHeader::class, $headers->all());
        $this->assertCount(count($headers->all()), $headers->all());
    }

    public function testHeadersHaveKey(): void
    {
        $expected = HttpHeaderBuilder::any()->build();

        $headers = new HttpHeaders($expected);

        $this->assertTrue($headers->has($expected->name()));
    }

    public function testHeaderCanBeRetrievedByKey(): void
    {
        $expected = HttpHeaderBuilder::any()->build();

        $headers = new HttpHeaders($expected);

        $this->assertEquals($expected, $headers->get($expected->name()));
    }

    public function testTheyAreMappable(): void
    {
        $headers = HttpHeadersBuilder::any()->build();

        $mappedHeaders = $headers->toArray();

        $this->assertIsArray($mappedHeaders);
        $this->assertNotEmpty($mappedHeaders);
        $this->assertEquals(
            array_keys($mappedHeaders),
            array_map(fn (HttpHeader $header): string => $header->name(), $headers->all())
        );
        $this->assertEquals(
            array_values($mappedHeaders),
            /** @return string[] */
            array_map(fn (HttpHeader $header): array => $header->values(), $headers->all())
        );
    }
}
