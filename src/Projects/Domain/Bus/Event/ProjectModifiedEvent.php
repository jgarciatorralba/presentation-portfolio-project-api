<?php

declare(strict_types=1);

namespace App\Projects\Domain\Bus\Event;

use App\Projects\Domain\ValueObject\ProjectId;
use App\Shared\Domain\Bus\Event\DomainEvent;

final readonly class ProjectModifiedEvent extends DomainEvent
{
    public function __construct(ProjectId $id)
    {
        parent::__construct($id->__toString());
    }

    public static function eventType(): string
    {
        return 'projects.domain.project_modified';
    }
}
