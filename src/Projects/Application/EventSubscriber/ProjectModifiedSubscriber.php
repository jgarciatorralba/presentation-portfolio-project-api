<?php

declare(strict_types=1);

namespace App\Projects\Application\EventSubscriber;

use App\Projects\Domain\Bus\Event\ProjectModifiedEvent;
use App\Projects\Domain\Service\UpdateProject;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Shared\Domain\Bus\Event\EventSubscriber;
use App\Shared\Domain\Contract\Log\Logger;

final readonly class ProjectModifiedSubscriber implements EventSubscriber
{
    public function __construct(
        private UpdateProject $updateProject,
        private Logger $logger
    ) {
    }

    public function __invoke(ProjectModifiedEvent $event): void
    {
        try {
            $this->updateProject->__invoke(
                ProjectId::create((int) $event->aggregateId())
            );

            $this->logger->info('ProjectModifiedEvent handled.', [
                'projectId' => $event->aggregateId(),
            ]);
        } catch (\Throwable $e) {
            $this->logger->error('ProjectModifiedEvent failed.', [
                'projectId' => $event->aggregateId(),
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
