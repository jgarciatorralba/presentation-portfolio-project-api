<?php

declare(strict_types=1);

namespace App\Tests\Builder\Projects\Application\Command\CreateProject;

use App\Projects\Application\Command\CreateProject\CreateProjectCommand;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class CreateProjectCommandBuilder implements BuilderInterface
{
    /**
     * @param string[]|null $topics
     */
    private function __construct(
        private int $id,
        private string $name,
        private ?string $description,
        private ?array $topics,
        private string $repository,
        private ?string $homepage,
        private bool $archived,
        private \DateTimeImmutable $lastPushedAt,
    ) {
    }

    public static function any(): self
    {
        return new self(
            id: FakeValueGenerator::integer(),
            name: FakeValueGenerator::string(),
            description: FakeValueGenerator::randomElement([
                null,
                FakeValueGenerator::text()
            ]),
            topics: FakeValueGenerator::randomElement([
                null,
                self::randomTopics()
            ]),
            repository: FakeValueGenerator::url(),
            homepage: FakeValueGenerator::randomElement([
                null,
                FakeValueGenerator::url()
            ]),
            archived: FakeValueGenerator::boolean(),
            lastPushedAt: FakeValueGenerator::dateTime()
        );
    }

    public function withId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string[]|null $topics
     */
    public function withTopics(?array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }

    public function withRepository(string $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    public function withHomepage(?string $homepage): self
    {
        $this->homepage = $homepage;

        return $this;
    }

    public function withArchived(bool $archived): self
    {
        $this->archived = $archived;

        return $this;
    }

    public function withLastPushedAt(\DateTimeImmutable $lastPushedAt): self
    {
        $this->lastPushedAt = $lastPushedAt;

        return $this;
    }

    public function build(): CreateProjectCommand
    {
        return new CreateProjectCommand(
            id: $this->id,
            name: $this->name,
            description: $this->description,
            topics: $this->topics,
            repository: $this->repository,
            homepage: $this->homepage,
            archived: $this->archived,
            lastPushedAt: $this->lastPushedAt
        );
    }

    /**
     * @return CreateProjectCommand[]
     */
    public static function buildMany(?int $amount = null): array
    {
        if ($amount === null) {
            $amount = FakeValueGenerator::integer(
                min: 1,
                max: 50
            );
        }

        $commands = [];
        for ($i = 0; $i < $amount; $i++) {
            $commands[] = self::any()->build();
        }

        return $commands;
    }

    /** @return string[] */
    private static function randomTopics(?int $numTopics = null): array
    {
        if ($numTopics === null) {
            $numTopics = FakeValueGenerator::integer(
                min: 1,
                max: 20
            );
        }

        $topics = [];
        for ($i = 0; $i < $numTopics; $i++) {
            $topics[] = FakeValueGenerator::string();
        }

        return $topics;
    }
}
