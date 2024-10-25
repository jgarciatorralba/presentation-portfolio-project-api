<?php

declare(strict_types=1);

namespace App\Projects\Application\EventSubscriber;

use App\Projects\Domain\Bus\Event\ProjectRemovedEvent;
use App\Projects\Domain\Service\DeleteProject;
use App\Shared\Domain\Bus\Event\EventSubscriber;

final readonly class ProjectRemovedSubscriber implements EventSubscriber
{
    public function __construct(
        private DeleteProject $deleteProject
    ) {
    }

    public function __invoke(ProjectRemovedEvent $event): void
    {
        $this->deleteProject->__invoke($event->project());
    }
}
