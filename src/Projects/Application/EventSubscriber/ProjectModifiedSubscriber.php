<?php

declare(strict_types=1);

namespace App\Projects\Application\EventSubscriber;

use App\Projects\Domain\Bus\Event\ProjectModifiedEvent;
use App\Shared\Domain\Bus\Event\EventSubscriber;

final readonly class ProjectModifiedSubscriber implements EventSubscriber
{
    public function __invoke(ProjectModifiedEvent $event): void
    {
        var_dump("ProjectModified: {$event->aggregateId()}");
    }
}
