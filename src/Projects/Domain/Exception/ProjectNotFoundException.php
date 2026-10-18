<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\Projects\Domain\ValueObject\ProjectId;
use App\Shared\Domain\DomainException;

final class ProjectNotFoundException extends DomainException
{
    public function __construct(private readonly ProjectId $id)
    {
        parent::__construct();
    }

    #[\Override]
    public function errorCode(): string
    {
        return 'project_not_found';
    }

    #[\Override]
    public function errorMessage(): string
    {
        return sprintf(
            "Project with id '%s' could not be found.",
            $this->id->value()
        );
    }
}
