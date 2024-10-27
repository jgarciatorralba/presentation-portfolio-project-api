<?php

declare(strict_types=1);

namespace App\Projects\Application\EventSubscriber;

use App\Projects\Domain\Bus\Event\ProjectAddedEvent;
use App\Projects\Domain\Service\CreateProject;
use App\Shared\Domain\Bus\Event\EventSubscriber;
use App\Shared\Domain\Contract\Logger;

final readonly class ProjectAddedSubscriber implements EventSubscriber
{
    public function __construct(
        private CreateProject $createProject,
        private Logger $logger
    ) {
    }

    public function __invoke(ProjectAddedEvent $event): void
    {
        try {
            $this->createProject->__invoke($event->project());

            $this->logger->info('ProjectAddedEvent handled.', [
                'projectId' => (string) $event->project()->id(),
            ]);
        } catch (\Throwable $e) {
            $this->logger->error('ProjectAddedEvent failed.', [
                'projectId' => (string) $event->project()->id(),
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
