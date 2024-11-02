<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\ValueObject\Http;

use App\Shared\Domain\Http\HttpHeader;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;
use PHPUnit\Framework\TestCase;

final class HttpHeaderTest extends TestCase
{
    public function testItIsCreated(): void
    {
        $name = 'Content-Type';
        $values = ['application/json'];

        $header = new HttpHeader($name, ...$values);

        $this->assertInstanceOf(HttpHeader::class, $header);
        $this->assertSame($name, $header->name());
        $this->assertSame($values, $header->values());
    }

    public function testItThrowsExceptionWhenHeaderNameIsInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid header name');

        $name = 'invalid_header';
        $values = [FakeValueGenerator::string()];

        new HttpHeader($name, ...$values);
    }
}
