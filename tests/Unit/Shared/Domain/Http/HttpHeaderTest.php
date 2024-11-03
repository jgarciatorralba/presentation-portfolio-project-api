<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\ValueObject\Http;

use App\Shared\Domain\Http\HttpHeader;
use App\Tests\Builder\Shared\Domain\Http\HttpHeaderBuilder;
use PHPUnit\Framework\TestCase;

final class HttpHeaderTest extends TestCase
{
    public function testItIsCreated(): void
    {
        $expected = HttpHeaderBuilder::any()->build();

        $actual = new HttpHeader($expected->name(), ...$expected->values());

        $this->assertInstanceOf(HttpHeader::class, $actual);
        $this->assertSame($expected->name(), $actual->name());
        $this->assertSame($expected->values(), $actual->values());
    }

    public function testItThrowsExceptionWhenHeaderNameIsInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid header name');

        $header = HttpHeaderBuilder::any()
            ->withName('invalid_header')
            ->build();
    }
}
