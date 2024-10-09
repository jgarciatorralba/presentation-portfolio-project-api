<?php

declare(strict_types=1);

namespace App\Projects\Application\EventSubscriber;

use App\Projects\Domain\Bus\Event\ProjectRemovedEvent;
use App\Shared\Domain\Bus\Event\EventSubscriber;

final readonly class ProjectRemovedSubscriber implements EventSubscriber
{
    public function __invoke(ProjectRemovedEvent $event): void
    {
        var_dump("ProjectRemoved: {$event->aggregateId()}");
    }
}
