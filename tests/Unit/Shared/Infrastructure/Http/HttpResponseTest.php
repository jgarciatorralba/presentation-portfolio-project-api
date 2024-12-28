<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Http;

use App\Shared\Domain\Http\HttpHeader;
use App\Shared\Domain\Http\HttpHeaders;
use App\Shared\Domain\Http\HttpProtocolVersion;
use App\Shared\Domain\Http\HttpStatusCode;
use App\Shared\Infrastructure\Http\HttpResponse;
use App\Shared\Infrastructure\Http\TemporaryFileStream;
use App\Tests\Builder\Shared\Domain\Http\HttpHeaderBuilder;
use App\Tests\Builder\Shared\Infrastructure\Http\HttpResponseBuilder;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;
use PHPUnit\Framework\TestCase;

final class HttpResponseTest extends TestCase
{
    public function testItIsCreated(): void
    {
        $expected = HttpResponseBuilder::any()->build();

        $headers = [];
        foreach ($expected->getHeaders() as $name => $values) {
            $headers[] = new HttpHeader($name, ...$values);
        }
        $httpHeaders = new HttpHeaders(...$headers);

        $statusCode = HttpStatusCode::from($expected->getStatusCode());
        $protocolVersion = HttpProtocolVersion::from($expected->getProtocolVersion());

        $actual = HttpResponse::create(
            body: $expected->getBody(),
            headers: $httpHeaders,
            statusCode: $statusCode,
            reasonPhrase: $expected->getReasonPhrase(),
            protocolVersion: $protocolVersion,
        );

        $this->assertEquals($expected, $actual);
    }

    public function testItIsCreatedWithStatus(): void
    {
        $expected = HttpResponseBuilder::any()->build();

        $statusCode = HttpStatusCode::HTTP_TOO_EARLY;

        $actual = $expected->withStatus($statusCode->value);

        $this->assertEquals($statusCode->value, $actual->getStatusCode());
        $this->assertEquals($statusCode->getReasonPhraseFromCode(), $actual->getReasonPhrase());
    }

    public function testItThrowsExceptionWhenStatusIsInvalid(): void
    {
        $expected = HttpResponseBuilder::any()->build();

        $this->expectException(\InvalidArgumentException::class);

        $expected->withStatus(0);
    }

    public function testItIsCreatedWithProtocolVersion(): void
    {
        $expected = HttpResponseBuilder::any()->build();

        $protocolVersion = HttpProtocolVersion::HTTP_3_0;

        $actual = $expected->withProtocolVersion($protocolVersion->value);

        $this->assertEquals($protocolVersion->value, $actual->getProtocolVersion());
        $this->assertEquals($expected->getStatusCode(), $actual->getStatusCode());
    }

    public function testItThrowsExceptionWhenProtocolVersionIsInvalid(): void
    {
        $expected = HttpResponseBuilder::any()->build();

        $this->expectException(\InvalidArgumentException::class);

        $expected->withProtocolVersion('invalid-version');
    }

    public function testItIsCreatedWithHeader(): void
    {
        $expected = HttpResponseBuilder::any()->build();

        $header = HttpHeaderBuilder::any()
            ->withName(FakeValueGenerator::randomElement(
                array_keys($expected->getHeaders())
            ))
            ->build();

        $actual = $expected->withHeader($header->name(), $header->values());

        $this->assertEquals($header->values(), $actual->getHeader($header->name()));
    }

    public function testItIsCreatedWithAddedHeader(): void
    {
        $expected = HttpResponseBuilder::any()->build();

        $header = HttpHeaderBuilder::any()
            ->withName(FakeValueGenerator::randomElement(
                array_keys($expected->getHeaders())
            ))
            ->build();

        $actual = $expected->withAddedHeader($header->name(), $header->values());

        $this->assertEquals(
            array_merge($expected->getHeader($header->name()), $header->values()),
            $actual->getHeader($header->name())
        );
    }

    public function testItThrowsExceptionWhenAddedHeaderNameIsInvalid(): void
    {
        $expected = HttpResponseBuilder::any()->build();

        $this->expectException(\InvalidArgumentException::class);

        $expected->withAddedHeader('invalid_header', []);
    }

    public function testItIsCreatedWithoutHeader(): void
    {
        $expected = HttpResponseBuilder::any()->build();

        $header = HttpHeaderBuilder::any()
            ->withName(FakeValueGenerator::randomElement(
                array_keys($expected->getHeaders())
            ))
            ->build();

        $actual = $expected->withoutHeader($header->name());

        $this->assertEmpty($actual->getHeader($header->name()));
    }

    public function testItIsCreatedWithBody(): void
    {
        $expected = HttpResponseBuilder::any()->build();

        $body = new TemporaryFileStream(FakeValueGenerator::text());

        $actual = $expected->withBody($body);

        $this->assertEquals($body->getSize(), $actual->getBody()->getSize());
        $this->assertEquals((string) $body, (string) $actual->getBody());
    }

    public function testItGetsAllHeaders(): void
    {
        $expected = HttpResponseBuilder::any()->build();

        $reflection = new \ReflectionClass($expected);
        $headers = $reflection->getProperty('headers');

        $actual = $expected->getHeaders();

        $this->assertEquals($headers->getValue($expected)->toArray(), $actual);
    }

    public function testItGetsHeader(): void
    {
        $expected = HttpHeaderBuilder::any()->build();

        $response = HttpResponseBuilder::any()
            ->build();

        $response = $response->hasHeader($expected->name())
            ? $response->withHeader($expected->name(), $expected->values())
            : $response->withAddedHeader($expected->name(), $expected->values());

        $actual = $response->getHeader($expected->name());

        $this->assertEquals($expected->values(), $actual);
    }

    public function testItGetsHeaderLine(): void
    {
        $expected = HttpHeaderBuilder::any()->build();

        $response = HttpResponseBuilder::any()
            ->build();

        $response = $response->hasHeader($expected->name())
            ? $response->withHeader($expected->name(), $expected->values())
            : $response->withAddedHeader($expected->name(), $expected->values());

        $actual = $response->getHeaderLine($expected->name());

        $this->assertEquals(implode(',', $expected->values()), $actual);
    }
}
