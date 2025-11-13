<?php

declare(strict_types=1);

namespace Tests\Support\Builder\Shared\Infrastructure\Http;

use App\Shared\Domain\Contract\Http\DataStream;
use App\Shared\Domain\Http\HttpHeader;
use App\Shared\Domain\Http\HttpHeaders;
use App\Shared\Domain\Http\HttpStatusCode;
use App\Shared\Domain\Http\HttpProtocolVersion;
use App\Shared\Infrastructure\Http\HttpResponse;
use App\Shared\Infrastructure\Http\TemporaryFileStream;
use Tests\Support\Builder\BuilderInterface;
use Tests\Support\Builder\Shared\Domain\Http\HttpHeadersBuilder;
use Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class HttpResponseBuilder implements BuilderInterface
{
    /**
     * @template T of HttpHeader
     * @param HttpHeaders<T> $headers
     */
    private function __construct(
        private HttpHeaders $headers,
        private DataStream $body,
        private HttpStatusCode $statusCode,
        private string $reasonPhrase,
        private HttpProtocolVersion $protocolVersion,
    ) {
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \TypeError
     * @throws \ValueError
     */
    public static function any(): self
    {
        return new self(
            headers: HttpHeadersBuilder::any()->build(),
            body: new TemporaryFileStream(FakeValueGenerator::text()),
            statusCode: HttpStatusCode::from(
                FakeValueGenerator::randomElement(
                    HttpStatusCode::values(),
                ),
            ),
            reasonPhrase: FakeValueGenerator::text(),
            protocolVersion: HttpProtocolVersion::fromServerEnvironment(),
        );
    }

    public function build(): HttpResponse
    {
        return HttpResponse::create(
            body: $this->body,
            headers: $this->headers,
            statusCode: $this->statusCode,
            reasonPhrase: $this->reasonPhrase,
            protocolVersion: $this->protocolVersion,
        );
    }
}
