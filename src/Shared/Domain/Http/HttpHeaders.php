<?php

declare(strict_types=1);

namespace App\Shared\Domain\Http;

use App\Shared\Domain\Contract\Collection;
use App\Shared\Domain\Contract\ArraySerializable;

/**
 * @template T of HttpHeader
 * @implements Collection<T>
 */
final readonly class HttpHeaders implements Collection, ArraySerializable
{
    /** @var array<string, HttpHeader> */
    private array $headers;

    /** @throws \InvalidArgumentException */
    public function __construct(HttpHeader ...$headers)
    {
        $mergedHeaders = [];
        foreach ($headers as $header) {
            $mergedHeader = $header;

            foreach ($mergedHeaders as $existingHeader) {
                if (strcasecmp($existingHeader->name(), $header->name()) === 0) {
                    $mergedHeader = new HttpHeader(
                        $existingHeader->name(),
                        ...$existingHeader->values(),
                        ...$header->values()
                    );

                    break;
                }
            }

            $mergedHeaders[$mergedHeader->name()] = $mergedHeader;
        }

        $this->headers = $mergedHeaders;
    }

    public function has(string $key): bool
    {
        return array_any(
            $this->headers,
            fn(HttpHeader $header): bool => strcasecmp($header->name(), $key) === 0
        );
    }

    /** @return HttpHeader|null */
    public function get(string $key): ?HttpHeader
    {
        foreach ($this->headers as $header) {
            if (strcasecmp($header->name(), $key) === 0) {
                return $header;
            }
        }

        return null;
    }

    /**
     * @return \Traversable<string, HttpHeader>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->headers);
    }

    /** @return array<string, string[]> */
    public function toArray(): array
    {
        $headersArray = [];
        foreach ($this->headers as $header) {
            $headersArray[$header->name()] = $header->values();
        }

        return $headersArray;
    }
}
