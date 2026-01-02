<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Symfony;

use App\Shared\Domain\Contract\Http\HttpClient as HttpClientContract;
use App\Shared\Domain\Contract\Http\HttpResponse as HttpResponseContract;
use App\Shared\Domain\Http\HttpHeader;
use App\Shared\Domain\Http\HttpHeaders;
use App\Shared\Domain\Http\HttpProtocolVersion;
use App\Shared\Domain\Http\HttpStatusCode;
use App\Shared\Domain\Http\QueryParams;
use App\Shared\Infrastructure\Http\HttpResponse;
use App\Shared\Infrastructure\Http\TemporaryFileStream;
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
        $this->client = $client ?? new RetryableHttpClient(HttpClient::create());
    }

    /**
     * @template T of HttpHeader
     * @param array{
     *      baseUri: string,
     *      headers: HttpHeaders<T>,
     *      query?: QueryParams,
     * } $httpOptions
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \ValueError
     * @throws \TypeError
     */
    public function fetch(string $url, array $httpOptions): HttpResponseContract
    {
        try {
            $response = $this->client
                ->request(
                    method: 'GET',
                    url: $url,
                    options: new HttpOptions()
                        ->setBaseUri($httpOptions['baseUri'])
                        ->setQuery(
                            empty($httpOptions['query'])
                                ? []
                                : $httpOptions['query']->toArray()
                        )
                        ->setHeaders($httpOptions['headers']->toArray())
                        ->toArray()
                );

            $statusCodeValue = $response->getStatusCode();
            $version = is_numeric($response->getInfo('http_version'))
                ? (float) $response->getInfo('http_version')
                : null;

            $headers = [];
            foreach ($response->getHeaders() as $name => $values) {
                $headers[] = new HttpHeader($name, ...$values);
            }
            $httpHeaders = new HttpHeaders(...$headers);

            $bodyString = json_encode([
                'content' => $response->toArray(),
                'error' => null
            ]);
            if (false === $bodyString) {
                throw new \RuntimeException('Failed to encode response body');
            }

            $httpResponseParams = [
                'body' => new TemporaryFileStream($bodyString),
                'headers' => $httpHeaders,
            ];
        } catch (
            TransportExceptionInterface
            | DecodingExceptionInterface
            | HttpExceptionInterface $e
        ) {
            $bodyString = json_encode([
                'content' => null,
                'error' => $e->getMessage()
            ]);
            if (false === $bodyString) {
                throw new \RuntimeException(
                    message: 'Failed to encode response body',
                    code: $e->getCode(),
                    previous: $e
                );
            }

            $httpResponseParams = [
                'body' => new TemporaryFileStream($bodyString),
                'headers' => $httpHeaders ?? new HttpHeaders()
            ];
        }

        $httpResponseParams['statusCode'] = isset($statusCodeValue)
            ? HttpStatusCode::from($statusCodeValue)
            : HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR;

        $protocolVersion = HttpProtocolVersion::tryFrom(number_format($version ?? 0, 1, '.', ''));
        if ($protocolVersion instanceof HttpProtocolVersion) {
            $httpResponseParams['protocolVersion'] = $protocolVersion;
        }

        return HttpResponse::create(...$httpResponseParams);
    }
}
