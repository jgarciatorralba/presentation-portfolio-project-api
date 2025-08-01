<?php

declare(strict_types=1);

namespace App\Projects\Domain\Bus\Event;

use App\Projects\Domain\Project;
use App\Shared\Domain\Bus\Event\DomainEvent;

final readonly class ProjectAddedEvent extends DomainEvent
{
    public function __construct(private Project $project)
    {
        parent::__construct($project->id()->__toString());
    }

    public function project(): Project
    {
        return $this->project;
    }

    public static function eventType(): string
    {
        return 'projects.domain.project_added';
    }
}
