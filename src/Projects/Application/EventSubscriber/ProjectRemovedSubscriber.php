<?php

declare(strict_types=1);

namespace App\Projects\Application\EventSubscriber;

use App\Projects\Domain\Bus\Event\ProjectRemovedEvent;
use App\Projects\Domain\Service\DeleteProject;
use App\Shared\Domain\Bus\Event\EventSubscriber;
use App\Shared\Domain\Contract\Logger;

final readonly class ProjectRemovedSubscriber implements EventSubscriber
{
    public function __construct(
        private DeleteProject $deleteProject,
        private Logger $logger
    ) {
    }

    public function __invoke(ProjectRemovedEvent $event): void
    {
        $this->deleteProject->__invoke($event->project());

        $this->logger->info('ProjectRemovedEvent handled.', [
            'eventId' => $event->eventId(),
            'projectId' => (string) $event->project()->id(),
        ]);
    }
}
