<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

class HttpResponse
{
    public function __construct(
        private readonly ?int $statusCode = null,
        private readonly ?string $error = null,
        private readonly ?string $content = null
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
}
