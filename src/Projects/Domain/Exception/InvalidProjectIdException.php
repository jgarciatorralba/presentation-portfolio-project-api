<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\Shared\Domain\DomainException;

final class InvalidProjectIdException extends DomainException
{
    public function __construct(private readonly int $id)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'invalid_project_id';
    }

    public function errorMessage(): string
    {
        return sprintf(
            "Invalid value for project id: '%s'. Must be a positive integer.",
            $this->id
        );
    }
}
