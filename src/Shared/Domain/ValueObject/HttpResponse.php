<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

readonly class HttpResponse
{
    /**
     * @param array<string, list<string>>|null $headers
     */
    public function __construct(
        private ?int $statusCode = null,
        private ?string $error = null,
        private ?string $content = null,
        private ?array $headers = null
    ) {
    }

    public function statusCode(): ?int
    {
        return $this->statusCode;
    }

    public function error(): ?string
    {
        return $this->error;
    }

    public function content(): ?string
    {
        return $this->content;
    }

    /**
     * @return array<string, list<string>>|null
     */
    public function headers(): ?array
    {
        return $this->headers;
    }
}
