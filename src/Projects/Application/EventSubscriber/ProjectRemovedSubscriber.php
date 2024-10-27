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
        try {
            $this->deleteProject->__invoke($event->project());

            $this->logger->info('ProjectRemovedEvent handled.', [
                'projectId' => (string) $event->project()->id(),
            ]);
        } catch (\Throwable $e) {
            $this->logger->error('ProjectRemovedEvent failed.', [
                'projectId' => (string) $event->project()->id(),
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
