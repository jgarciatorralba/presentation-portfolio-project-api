<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\Shared\Domain\DomainException;

class ProjectAlreadyExistsException extends DomainException
{
    public function __construct(private readonly int $id)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'project_already_exists';
    }

    public function errorMessage(): string
    {
        return sprintf(
            "Project with id '%s' already exists.",
            $this->id
        );
    }
}
