<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use App\Shared\Domain\DomainException;

final class InvalidCodeRepositoryUrlException extends DomainException
{
    public function __construct(
        private readonly string $url,
        private readonly string $domain,
    ) {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'invalid_code_repository';
    }

    public function errorMessage(): string
    {
        return sprintf(
            "Invalid value for code repository: '%s'. Must belong to %s domain.",
            $this->url,
            $this->domain
        );
    }
}
