<?php

declare(strict_types=1);

namespace App\Projects\Application\EventSubscriber;

use App\Projects\Domain\Bus\Event\ProjectAddedEvent;
use App\Projects\Domain\Project;
use App\Projects\Domain\Service\CreateProject;
use App\Projects\Domain\ValueObject\ProjectDetails;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Projects\Domain\ValueObject\ProjectRepositoryUrl;
use App\Projects\Domain\ValueObject\ProjectUrls;
use App\Shared\Domain\Bus\Event\EventSubscriber;
use App\Shared\Domain\Service\LocalDateTimeZoneConverter;
use App\Shared\Domain\ValueObject\Url;

final readonly class ProjectAddedSubscriber implements EventSubscriber
{
    public function __construct(
        private CreateProject $createProject,
        private LocalDateTimeZoneConverter $dateTimeConverter
    ) {
    }

    public function __invoke(ProjectAddedEvent $event): void
    {
        $projectDetails = ProjectDetails::create(
            name: $event->project()->details()->name(),
            description: $event->project()->details()->description(),
            topics: $event->project()->details()->topics()
        );

        $repository = ProjectRepositoryUrl::fromString(
            $event->project()->urls()->repository()->value()
        );
        $homepage = $event->project()->urls()->homepage() !== null
            ? Url::fromString($event->project()->urls()->homepage()->value())
            : null;

        $projectUrls = ProjectUrls::create(
            repository: $repository,
            homepage: $homepage
        );

        $projectId = ProjectId::create($event->project()->id()->value());

        $project = Project::create(
            id: $projectId,
            details: $projectDetails,
            urls: $projectUrls,
            archived: $event->project()->archived(),
            lastPushedAt: $this->dateTimeConverter
                ->convert($event->project()->lastPushedAt())
        );

        $this->createProject->__invoke($project);
    }
}
