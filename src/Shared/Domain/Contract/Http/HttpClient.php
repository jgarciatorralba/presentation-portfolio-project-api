<?php

declare(strict_types=1);

namespace App\Shared\Domain\Contract\Http;

use App\Shared\Domain\ValueObject\Http\HttpHeader;
use App\Shared\Domain\ValueObject\Http\HttpHeaders;
use App\Shared\Domain\ValueObject\Http\QueryParams;

interface HttpClient
{
    /**
     * @template T of HttpHeader
     * @param array{
     *      baseUri: string,
     *      headers: HttpHeaders<T>,
     *      query?: QueryParams,
     * } $httpOptions
     */
    public function fetch(string $url, array $httpOptions): HttpResponse;
}
