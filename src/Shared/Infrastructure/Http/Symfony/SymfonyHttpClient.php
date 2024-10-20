<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Symfony;

use App\Shared\Domain\Contract\Http\HttpClient as HttpClientContract;
use App\Shared\Domain\ValueObject\Http\HttpHeader;
use App\Shared\Domain\ValueObject\Http\HttpHeaders;
use App\Shared\Domain\ValueObject\Http\HttpProtocolVersion;
use App\Shared\Domain\ValueObject\Http\HttpResponse;
use App\Shared\Domain\ValueObject\Http\HttpStatusCode;
use App\Shared\Domain\ValueObject\Http\QueryParams;
use App\Shared\Domain\ValueObject\Http\TemporaryFileStream;
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
     * @template T of HttpHeader
     * @param array{
     *      baseUri: string,
     *      headers: HttpHeaders<T>,
     *      query?: QueryParams,
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
                        ->setBaseUri($httpOptions['baseUri'])
                        ->setQuery(
                            !empty($httpOptions['query'])
                                ? $httpOptions['query']->toArray()
                                : []
                        )
                        ->setHeaders($httpOptions['headers']->toArray())
                        ->toArray()
                );

            $statusCodeValue = $response->getStatusCode();
            $version = $response->getInfo('http_version');

            $headers = [];
            foreach ($response->getHeaders() as $name => $values) {
                $headers[] = new HttpHeader($name, ...$values);
            }
            $httpHeaders = new HttpHeaders(...$headers);

            $httpResponseParams = [
                'body' => new TemporaryFileStream(json_encode([
                    'content' => $response->toArray(),
                    'error' => null
                ])),
                'headers' => $httpHeaders,
            ];
        } catch (
            TransportExceptionInterface
            | DecodingExceptionInterface
            | HttpExceptionInterface $e
        ) {
            $httpResponseParams = [
                'body' => new TemporaryFileStream(json_encode([
                    'content' => null,
                    'error' => $e->getMessage()
                ])),
                'headers' => $httpHeaders ?? new HttpHeaders()
            ];
        } finally {
            $httpResponseParams['statusCode'] = isset($statusCodeValue)
                ? HttpStatusCode::from($statusCodeValue)
                : HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR;

            if (!empty($version) && !empty($protocolVersion = HttpProtocolVersion::tryFrom((string) $version))) {
                $httpResponseParams['protocolVersion'] = $protocolVersion;
            }

            return HttpResponse::create(...$httpResponseParams);
        }
    }
}
