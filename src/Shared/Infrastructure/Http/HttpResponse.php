<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http;

use App\Shared\Domain\Contract\Http\HttpResponse as HttpResponseInterface;
use App\Shared\Domain\Contract\Http\DataStream;
use App\Shared\Domain\Http\HttpHeader;
use App\Shared\Domain\Http\HttpHeaders;
use App\Shared\Domain\Http\HttpProtocolVersion;
use App\Shared\Domain\Http\HttpStatusCode;
use Psr\Http\Message\StreamInterface;

readonly class HttpResponse implements HttpResponseInterface
{
    /**
     * @template T of HttpHeader
     * @param HttpHeaders<T> $headers
     */
    final private function __construct(
        private HttpHeaders $headers,
        private DataStream $body,
        private HttpStatusCode $statusCode,
        private string $reasonPhrase,
        private HttpProtocolVersion $protocolVersion,
    ) {
    }

    /**
     * @template T of HttpHeader
     * @param HttpHeaders<T> $headers
     */
    public static function create(
        DataStream $body,
        HttpHeaders $headers = new HttpHeaders(),
        HttpStatusCode $statusCode = HttpStatusCode::HTTP_OK,
        string $reasonPhrase = '',
        ?HttpProtocolVersion $protocolVersion = null,
    ): static {
        return new static(
            headers: $headers,
            body: $body,
            statusCode: $statusCode,
            reasonPhrase: empty($reasonPhrase)
                ? $statusCode->getReasonPhraseFromCode()
                : $reasonPhrase,
            protocolVersion: $protocolVersion ?? HttpProtocolVersion::fromServerEnvironment(),
        );
    }

    public function getStatusCode(): int
    {
        return $this->statusCode->value;
    }

    /** @throws \InvalidArgumentException */
    public function withStatus(int $code, string $reasonPhrase = ''): static
    {
        try {
            $statusCode = HttpStatusCode::from($code);

            return new static(
                body: $this->body,
                statusCode: $statusCode,
                reasonPhrase: empty($reasonPhrase)
                    ? $statusCode->getReasonPhraseFromCode()
                    : $reasonPhrase,
                headers: $this->headers,
                protocolVersion: $this->protocolVersion,
            );
        } catch (\ValueError | \TypeError) {
            throw new \InvalidArgumentException('Invalid status code value');
        }
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion->value;
    }

    /** @throws \InvalidArgumentException */
    public function withProtocolVersion(string $version): static
    {
        try {
            $protocolVersion = HttpProtocolVersion::from($version);

            return new static(
                body: $this->body,
                statusCode: $this->statusCode,
                reasonPhrase: $this->reasonPhrase,
                headers: $this->headers,
                protocolVersion: $protocolVersion,
            );
        } catch (\ValueError | \TypeError) {
            throw new \InvalidArgumentException('Invalid protocol version value');
        }
    }

    /** @return array<string, string[]> */
    public function getHeaders(): array
    {
        return $this->headers->toArray();
    }

    public function hasHeader(string $name): bool
    {
        return $this->headers->has($name);
    }

    /** @return string[] */
    public function getHeader(string $name): array
    {
        $foundHeader = $this->headers->get($name);

        return $foundHeader instanceof HttpHeader
            ? $foundHeader->values()
            : [];
    }

    public function getHeaderLine(string $name): string
    {
        return implode(',', $this->getHeader($name));
    }

    /**
     * @param string|string[] $value
     *
     * @throws \InvalidArgumentException
     */
    public function withHeader(string $name, mixed $value): static
    {
        $newHeaders = [];
        foreach ($this->headers->all() as $header) {
            $newHeaders[] = strcasecmp($header->name(), $name) === 0
                ? new HttpHeader($header->name(), ...(array) $value)
                : $header;
        }

        return new static(
            body: $this->body,
            statusCode: $this->statusCode,
            reasonPhrase: $this->reasonPhrase,
            headers: new HttpHeaders(...$newHeaders),
            protocolVersion: $this->protocolVersion,
        );
    }

    /**
     * @param string|string[] $value
     *
     * @throws \InvalidArgumentException
     */
    public function withAddedHeader(string $name, mixed $value): self
    {
        if (!$this->hasHeader($name)) {
            $headers = [
                ...$this->headers->all(),
                new HttpHeader($name, ...(array) $value),
            ];

            return new static(
                body: $this->body,
                statusCode: $this->statusCode,
                reasonPhrase: $this->reasonPhrase,
                headers: new HttpHeaders(...$headers),
                protocolVersion: $this->protocolVersion,
            );
        }

        $headerValues = $this->getHeader($name);
        return $this->withHeader(
            $name,
            array_merge($headerValues, (array) $value)
        );
    }

    /** @throws \InvalidArgumentException */
    public function withoutHeader(string $name): static
    {
        $newHeaders = [];
        foreach ($this->headers->all() as $header) {
            if (strcasecmp($header->name(), $name) !== 0) {
                $newHeaders[] = $header;
            }
        }

        return new static(
            body: $this->body,
            statusCode: $this->statusCode,
            reasonPhrase: $this->reasonPhrase,
            headers: new HttpHeaders(...$newHeaders),
            protocolVersion: $this->protocolVersion,
        );
    }

    public function getBody(): DataStream
    {
        return $this->body;
    }

    /** @throws \RuntimeException */
    public function withBody(StreamInterface $body): static
    {
        $temp = new TemporaryFileStream((string) $body);

        return new static(
            body: $temp,
            statusCode: $this->statusCode,
            reasonPhrase: $this->reasonPhrase,
            headers: $this->headers,
            protocolVersion: $this->protocolVersion,
        );
    }
}
