<?php

declare(strict_types=1);

namespace App\Projects\Application\EventSubscriber;

use App\Projects\Domain\Bus\Event\ProjectAddedEvent;
use App\Shared\Domain\Bus\Event\EventSubscriber;

final readonly class ProjectAddedSubscriber implements EventSubscriber
{
    public function __invoke(ProjectAddedEvent $event): void
    {
        var_dump("ProjectAdded: {$event->aggregateId()}");
    }
}
