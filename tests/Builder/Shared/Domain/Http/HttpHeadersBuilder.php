<?php

declare(strict_types=1);

namespace App\Tests\Builder\Shared\Domain\Http;

use App\Shared\Domain\Http\HttpHeader;
use App\Shared\Domain\Http\HttpHeaders;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class HttpHeadersBuilder implements BuilderInterface
{
    private const int MIN_HEADERS = 1;
    private const int MAX_HEADERS = 10;

    /** @var HttpHeader[] $headers */
    private array $headers;

    private function __construct(
        HttpHeader ...$headers
    ) {
        $this->headers = $headers;
    }

    /** @throws \InvalidArgumentException */
    public static function any(): self
    {
        return new self(
            ...self::randomHeaders(),
        );
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return HttpHeaders<HttpHeader>
     */
    public function build(): HttpHeaders
    {
        return new HttpHeaders(
            ...$this->headers,
        );
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return HttpHeader[]
     */
    private static function randomHeaders(?int $numHeaders = null): array
    {
        if ($numHeaders === null) {
            $numHeaders = FakeValueGenerator::integer(
                min: self::MIN_HEADERS,
                max: self::MAX_HEADERS
            );
        }

        $headers = [];
        for ($i = 0; $i < $numHeaders; $i++) {
            $headers[] = HttpHeaderBuilder::any()->build();
        }

        return $headers;
    }
}
