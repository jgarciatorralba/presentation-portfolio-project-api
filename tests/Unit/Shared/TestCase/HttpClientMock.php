<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\TestCase;

use App\Shared\Domain\Contract\Http\HttpClient;
use App\Shared\Domain\Http\HttpHeader;
use App\Shared\Domain\Http\HttpHeaders;
use App\Shared\Domain\Http\HttpStatusCode;
use App\Shared\Infrastructure\Http\HttpResponse;
use App\Shared\Infrastructure\Http\TemporaryFileStream;
use Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class HttpClientMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return HttpClient::class;
    }

    /**
     * @param list<array{
     *      content: array<string, mixed>,
     *      error: string|null,
     *      headers: list<HttpHeader>,
     *      statusCode?: HttpStatusCode,
     * }> $chunks
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function shouldFetchByChunks(array $chunks): void
    {
        $this->mock
            ->expects($this->exactly(count($chunks)))
            ->method('fetch')
            ->willReturnCallback(
                function () use (&$chunks): HttpResponse {
                    $chunk = array_shift($chunks);

                    $bodyString = json_encode([
                        'content' => $chunk['content'],
                        'error' => $chunk['error'],
                    ]);

                    if (false === $bodyString) {
                        throw new \RuntimeException('Failed to encode response body');
                    }

                    return HttpResponse::create(
                        body: new TemporaryFileStream($bodyString),
                        headers: new HttpHeaders(...$chunk['headers']),
                        statusCode: $chunk['statusCode'] ?? HttpStatusCode::HTTP_OK
                    );
                }
            );
    }
}
