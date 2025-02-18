<?php

declare(strict_types=1);

namespace App\Projects\Application\Bus\Event;

use App\Shared\Application\Bus\Event\ApplicationEvent;

final readonly class SyncProjectsRequestedEvent extends ApplicationEvent
{
    public static function eventType(): string
    {
        return 'projects.application.sync_projects_requested';
    }
}
