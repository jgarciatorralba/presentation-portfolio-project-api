<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Http\Symfony;

use App\Shared\Domain\Contract\Http\HttpClient;
use App\Shared\Domain\Contract\Http\HttpResponse;
use App\Shared\Domain\Http\HttpHeader;
use App\Shared\Domain\Http\HttpHeaders;
use App\Shared\Domain\Http\HttpStatusCode;
use App\Shared\Domain\Http\QueryParam;
use App\Shared\Domain\Http\QueryParams;
use App\Shared\Infrastructure\Http\Symfony\SymfonyHttpClient;
use App\Shared\Infrastructure\Http\TemporaryFileStream;
use PHPUnit\Framework\TestCase;

final class SymfonyHttpClientTest extends TestCase
{
    private const string BASE_URI = 'https://jsonplaceholder.typicode.com';

    private HttpClient $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = new SymfonyHttpClient();
    }

    public function testItFetchesUrl(): void
    {
        $httpOptions = [
            'baseUri' => self::BASE_URI,
            'headers' => new HttpHeaders(
                new HttpHeader('Content-Type', 'application/json'),
            ),
            'query' => new QueryParams(
                new QueryParam('userId', '1')
            ),
        ];

        $response = $this->httpClient->fetch('/posts', $httpOptions);

        $this->assertInstanceOf(HttpResponse::class, $response);
        $this->assertSame(
            HttpStatusCode::HTTP_OK->value,
            $response->getStatusCode()
        );
        $this->assertSame(
            (HttpStatusCode::HTTP_OK)->getReasonPhraseFromCode(),
            $response->getReasonPhrase()
        );
        $this->assertTrue($response->hasHeader('Content-Type'));
        $this->assertNotEmpty(
            $response->getProtocolVersion()
        );
        $this->assertInstanceOf(TemporaryFileStream::class, $response->getBody());
        $this->assertNotEmpty($content = $response->getBody()->getContents());
        $this->assertIsArray(json_decode($content, true));
    }
}
