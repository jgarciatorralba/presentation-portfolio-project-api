<?php

declare(strict_types=1);

namespace App\Projects\Application\Command\CreateProject;

use App\Projects\Domain\Project;
use App\Projects\Domain\ValueObject\ProjectDetails;
use App\Projects\Domain\ValueObject\ProjectUrls;
use App\Projects\Domain\Service\CreateProject;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Projects\Domain\ValueObject\ProjectRepositoryUrl;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Service\LocalDateTimeZoneConverter;
use App\Shared\Domain\ValueObject\Url;

final readonly class CreateProjectCommandHandler implements CommandHandler
{
    public function __construct(
        private CreateProject $createProject,
        private LocalDateTimeZoneConverter $dateTimeConverter
    ) {
    }

    public function __invoke(CreateProjectCommand $command): void
    {
        $projectDetails = ProjectDetails::create(
            name: $command->name(),
            description: $command->description(),
            topics: $command->topics()
        );

        $repository = ProjectRepositoryUrl::fromString($command->repository());
        $homepage = $command->homepage() !== null ? Url::fromString($command->homepage()) : null;

        $projectUrls = ProjectUrls::create(
            repository: $repository,
            homepage: $homepage
        );

        $projectId = ProjectId::create($command->id());

        $project = Project::create(
            id: $projectId,
            details: $projectDetails,
            urls: $projectUrls,
            archived: $command->archived(),
            lastPushedAt: $this->dateTimeConverter
                ->convert($command->lastPushedAt())
        );

        $this->createProject->__invoke($project);
    }
}
