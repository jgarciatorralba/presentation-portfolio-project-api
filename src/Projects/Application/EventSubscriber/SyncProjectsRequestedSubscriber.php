<?php

declare(strict_types=1);

namespace App\Projects\Application\EventSubscriber;

use App\Projects\Application\Bus\Event\SyncProjectsRequestedEvent;
use App\Projects\Domain\Service\GetAllProjects;
use App\Projects\Domain\Service\RequestExternalProjects;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Domain\Bus\Event\EventSubscriber;

final readonly class SyncProjectsRequestedSubscriber implements EventSubscriber
{
    public function __construct(
        private RequestExternalProjects $requestExternalProjects,
        private GetAllProjects $getAllProjects,
        private EventBus $eventBus
    ) {
    }

    /** @throws \InvalidArgumentException */
    public function __invoke(SyncProjectsRequestedEvent $event): void
    {
        $storedProjects = $this->getAllProjects->__invoke();
        $externalProjects = $this->requestExternalProjects->__invoke();
        $events = [];

        foreach ($externalProjects->all() as $project) {
            if (!$storedProjects->has((string) $project->id())) {
                $project->create();
            } elseif (!$project->equals($storedProjects->get((string) $project->id()))) {
                $storedProjects->get((string) $project->id())->synchronizeWith($project);
            }

            $events = [...$events, ...$project->pullEvents()];
        }

        foreach ($storedProjects->all() as $project) {
            if (!$externalProjects->has((string) $project->id())) {
                $project->erase();
            }

            $events = [...$events, ...$project->pullEvents()];
        }

        if (count($events) > 0) {
            $this->eventBus->publish(...$events);
        }
    }
}
