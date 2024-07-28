<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\Shared\Domain\DomainException;
use App\Shared\Domain\ValueObject\Uuid;

class ProjectNotFoundException extends DomainException
{
    public function __construct(private readonly Uuid $id)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'project_not_found';
    }

    public function errorMessage(): string
    {
        return sprintf(
            "Project with id '%s' could not be found.",
            $this->id->value()
        );
    }
}
