<?php

declare(strict_types=1);

namespace App\Projects\Application\EventSubscriber;

use App\Projects\Domain\Bus\Event\ProjectModifiedEvent;
use App\Projects\Domain\Service\UpdateProject;
use App\Shared\Domain\Bus\Event\EventSubscriber;
use App\Shared\Domain\Contract\Logger;

final readonly class ProjectModifiedSubscriber implements EventSubscriber
{
    public function __construct(
        private UpdateProject $updateProject,
        private Logger $logger
    ) {
    }

    public function __invoke(ProjectModifiedEvent $event): void
    {
        $this->updateProject->__invoke($event->project());

        $this->logger->info('ProjectModifiedEvent handled.', [
            'eventId' => $event->eventId(),
            'projectId' => (string) $event->project()->id(),
        ]);
    }
}
