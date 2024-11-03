<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Http;

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

    public function testTheyAreMergedWhenHeaderNameIsTheSame(): void
    {
        $firstHeader = HttpHeaderBuilder::any()
            ->withName('a-valid-header')
            ->build();

        $secondHeader = HttpHeaderBuilder::any()
        ->withName('A-Valid-Header')
        ->build();

        $headers = new HttpHeaders($firstHeader, $secondHeader);

        $this->assertCount(1, $headers->all());
        $this->assertSame(
            count($firstHeader->values()) + count($secondHeader->values()),
            count($headers->get($firstHeader->name())->values())
        );
    }
}
