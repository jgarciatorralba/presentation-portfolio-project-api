<?php

declare(strict_types=1);

namespace App\Projects\Application\Command\CreateProject;

use App\Projects\Domain\Project;
use App\Projects\Domain\ProjectDetails;
use App\Projects\Domain\ProjectUrls;
use App\Projects\Domain\Service\CreateProject;
use App\Projects\Domain\Service\LocalDateTimeZoneConverter;
use App\Shared\Domain\Bus\Command\CommandHandler;

final class CreateProjectCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CreateProject $createProject,
        private readonly LocalDateTimeZoneConverter $dateTimeConverter
    ) {
    }

    public function __invoke(
        CreateProjectCommand $command
    ): void {
        $projectDetails = ProjectDetails::create(
            name: $command->name(),
            description: $command->description(),
            topics: $command->topics()
        );

        $projectUrls = ProjectUrls::create(
            repository: $command->repository(),
            homepage: $command->homepage()
        );

        $project = Project::create(
            id: $command->id(),
            details: $projectDetails,
            urls: $projectUrls,
            archived: $command->archived(),
            lastPushedAt: $this->dateTimeConverter->convert($command->lastPushedAt()),
            createdAt: $command->createdAt(),
            updatedAt: $command->createdAt()
        );

        $this->createProject->__invoke($project);
    }
}
