<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\Shared\Domain\DomainException;

final class InvalidProjectRepositoryUrlException extends DomainException
{
    public function __construct(private readonly string $url)
    {
        parent::__construct();
    }

    #[\Override]
    public function errorCode(): string
    {
        return 'invalid_project_repository';
    }

    #[\Override]
    public function errorMessage(): string
    {
        return sprintf(
            "Invalid value for project repository: '%s'. Must belong to GitHub domain.",
            $this->url
        );
    }
}
