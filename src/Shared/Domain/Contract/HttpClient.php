<?php

declare(strict_types=1);

namespace App\Shared\Domain\Contract;

use App\Shared\Domain\ValueObject\HttpResponse;

interface HttpClient
{
    /**
     * @param array{
     *      base_uri: string,
     *      headers: array<string, string>,
     *      query: array<string, mixed>
     * } $httpOptions
     */
    public function fetch(string $url, array $httpOptions): HttpResponse;
}
