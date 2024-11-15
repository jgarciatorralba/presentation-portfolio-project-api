<?php

declare(strict_types=1);

namespace App\Shared\Domain\Http;

use App\Shared\Domain\Contract\Collection;
use App\Shared\Domain\Contract\Mappable;

/**
 * @template T of HttpHeader
 * @implements Collection<T>
 */
final readonly class HttpHeaders implements Collection, Mappable
{
    /** @var list<HttpHeader> */
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
                        ...array_merge($existingHeader->values(), $header->values())
                    );

                    break;
                }
            }

            $mergedHeaders[$mergedHeader->name()] = $mergedHeader;
        }

        $this->headers = array_values($mergedHeaders);
    }

    /** @return HttpHeader[] */
    public function all(): array
    {
        return $this->headers;
    }

    public function has(string $key): bool
    {
        foreach ($this->headers as $header) {
            if (strcasecmp($header->name(), $key) === 0) {
                return true;
            }
        }

        return false;
    }

    public function get(string $key): ?HttpHeader
    {
        foreach ($this->headers as $header) {
            if (strcasecmp($header->name(), $key) === 0) {
                return $header;
            }
        }

        return null;
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
