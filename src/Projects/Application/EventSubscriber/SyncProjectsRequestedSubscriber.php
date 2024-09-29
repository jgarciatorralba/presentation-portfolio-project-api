<?php

declare(strict_types=1);

namespace App\Projects\Application\EventSubscriber;

use App\Projects\Application\Bus\Event\SyncProjectsRequestedEvent;
use App\Projects\Domain\Bus\Event\ProjectAddedEvent;
use App\Projects\Domain\Bus\Event\ProjectModifiedEvent;
use App\Projects\Domain\Bus\Event\ProjectRemovedEvent;
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

    public function __invoke(SyncProjectsRequestedEvent $event): void
    {
        $storedProjects = $this->getAllProjects->__invoke();
        $externalProjects = $this->requestExternalProjects->__invoke();

        foreach ($externalProjects as $projectId => $project) {
            if (!isset($storedProjects[$projectId])) {
                $this->eventBus->publish(
                    new ProjectAddedEvent($project)
                );
            } elseif (!$project->equals($storedProjects[$projectId])) {
                $this->eventBus->publish(
                    new ProjectModifiedEvent($project)
                );
            }
        }

        foreach ($storedProjects as $projectId => $project) {
            if (!isset($externalProjects[$projectId])) {
                $this->eventBus->publish(
                    new ProjectRemovedEvent($projectId)
                );
            }
        }
    }
}
