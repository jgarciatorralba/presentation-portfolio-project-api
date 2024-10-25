<?php

declare(strict_types=1);

namespace App\Projects\Application\EventSubscriber;

use App\Projects\Domain\Bus\Event\ProjectAddedEvent;
use App\Projects\Domain\Service\CreateProject;
use App\Shared\Domain\Bus\Event\EventSubscriber;

final readonly class ProjectAddedSubscriber implements EventSubscriber
{
    public function __construct(
        private CreateProject $createProject
    ) {
    }

    public function __invoke(ProjectAddedEvent $event): void
    {
        $this->createProject->__invoke($event->project());
    }
}
