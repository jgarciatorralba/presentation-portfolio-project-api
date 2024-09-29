<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Symfony;

use App\Shared\Domain\Contract\HttpClient as HttpClientContract;
use App\Shared\Domain\ValueObject\HttpResponse;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpOptions;
use Symfony\Component\HttpClient\RetryableHttpClient;

final class SymfonyHttpClient implements HttpClientContract
{
    public function __construct(
        private ?HttpClientInterface $client = null
    ) {
        $this->client = new RetryableHttpClient(HttpClient::create());
    }

    /**
     * @param array{
     *      base_uri: string,
     *      headers: array<string, string>,
     *      query?: array<string, mixed>
     * } $httpOptions
     */
    public function fetch(string $url, array $httpOptions): HttpResponse
    {
        try {
            $response = $this->client
                ->request(
                    method: 'GET',
                    url: $url,
                    options: (new HttpOptions())
                        ->setBaseUri($httpOptions['base_uri'])
                        ->setQuery($httpOptions['query'] ?? [])
                        ->setHeaders($httpOptions['headers'])
                        ->toArray()
                );

            $statusCode = $response->getStatusCode();
            $content = $response->getContent();
            $headers = $response->getHeaders();

            return new HttpResponse(
                statusCode: $statusCode,
                content: $content,
                headers: $headers
            );
        } catch (
            TransportExceptionInterface
            | DecodingExceptionInterface
            | HttpExceptionInterface $e
        ) {
            return new HttpResponse(
                statusCode: $statusCode ?? null,
                error: $e->getMessage()
            );
        }
    }
}
