<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Application\Command\CreateProject\Factory;

use App\Projects\Application\Command\CreateProject\CreateProjectCommand;
use App\Projects\Domain\Project;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class CreateProjectCommandFactory
{
    /**
     * @param string[]|null $topics
     */
    public static function create(
        ?int $id = null,
        ?string $name = null,
        ?string $description = null,
        ?array $topics = null,
        ?string $repository = null,
        ?string $homepage = null,
        ?bool $archived = null,
        ?\DateTimeImmutable $lastPushedAt = null
    ): CreateProjectCommand {
        return new CreateProjectCommand(
            id: $id ?? FakeValueGenerator::integer(),
            name: $name ?? FakeValueGenerator::string(),
            description: $description ?? FakeValueGenerator::randomElement([null, FakeValueGenerator::text()]),
            topics: $topics ?? FakeValueGenerator::randomElement([null, self::generateRandomTopics()]),
            repository: $repository ?? FakeValueGenerator::string(),
            homepage: $homepage ?? FakeValueGenerator::randomElement([null, FakeValueGenerator::string()]),
            archived: !is_null($archived) ? $archived : FakeValueGenerator::boolean(),
            lastPushedAt: $lastPushedAt ?? FakeValueGenerator::dateTime()
        );
    }

    public static function createFromProject(Project $project): CreateProjectCommand
    {
        return new CreateProjectCommand(
            id: $project->id(),
            name: $project->details()->name(),
            description: $project->details()->description(),
            topics: $project->details()->topics(),
            repository: $project->urls()->repository(),
            homepage: $project->urls()->homepage(),
            archived: $project->archived(),
            lastPushedAt: $project->lastPushedAt()
        );
    }

    /**
     * @return CreateProjectCommand[]
     */
    public static function createMany(?int $amount = null): array
    {
        if ($amount === null) {
            $amount = FakeValueGenerator::integer(1, 50);
        }

        $commands = [];
        for ($i = 0; $i < $amount; $i++) {
            $commands[] = self::create();
        }

        return $commands;
    }

    /** @return string[] */
    private static function generateRandomTopics(?int $numTopics = null): array
    {
        if ($numTopics === null) {
            $numTopics = FakeValueGenerator::integer(1, 20);
        }

        $topics = [];
        for ($i = 0; $i < $numTopics; $i++) {
            $topics[] = FakeValueGenerator::string();
        }

        return $topics;
    }
}
