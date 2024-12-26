<?php

declare(strict_types=1);

namespace App\Tests\Builder\Projects\Domain;

use App\Projects\Domain\Exception\InvalidProjectRepositoryUrlException;
use App\Projects\Domain\Project;
use App\Projects\Domain\ValueObject\ProjectDetails;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Projects\Domain\ValueObject\ProjectUrls;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Builder\Projects\Domain\ValueObject\ProjectDetailsBuilder;
use App\Tests\Builder\Projects\Domain\ValueObject\ProjectIdBuilder;
use App\Tests\Builder\Projects\Domain\ValueObject\ProjectUrlsBuilder;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class ProjectBuilder implements BuilderInterface
{
    private const int MIN_PROJECTS = 1;
    private const int MAX_PROJECTS = 100;

    private function __construct(
        private ProjectId $id,
        private ProjectDetails $details,
        private ProjectUrls $urls,
        private bool $archived,
        private \DateTimeImmutable $lastPushedAt,
        private \DateTimeImmutable $createdAt,
        private \DateTimeImmutable $updatedAt
    ) {
    }

    /**
     * @throws InvalidProjectRepositoryUrlException
     * @throws \InvalidArgumentException
     */
    public static function any(): self
    {
        return new self(
            id: ProjectIdBuilder::any()->build(),
            details: ProjectDetailsBuilder::any()->build(),
            urls: ProjectUrlsBuilder::any()->build(),
            archived: FakeValueGenerator::boolean(),
            lastPushedAt: FakeValueGenerator::dateTime(),
            createdAt: FakeValueGenerator::dateTime(),
            updatedAt: FakeValueGenerator::dateTime()
        );
    }

    /**
     * @throws InvalidProjectRepositoryUrlException
     * @throws \InvalidArgumentException
     *
     * @return Project[]
     */
    public static function buildMany(?int $amount = null): array
    {
        if ($amount === null) {
            $amount = FakeValueGenerator::integer(
                min: self::MIN_PROJECTS,
                max: self::MAX_PROJECTS
            );
        }

        $projects = [];

        $i = 0;
        while ($i < $amount) {
            $project = self::any()->build();
            if (!in_array($project->id()->value(), array_keys($projects))) {
                $projects[$project->id()->value()] = $project;
                $i++;
            }
        }

        return array_values($projects);
    }

    public function withId(ProjectId $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function withCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function withUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function build(): Project
    {
        $project = Project::recreate(
            id: $this->id,
            details: $this->details,
            urls: $this->urls,
            archived: $this->archived,
            lastPushedAt: $this->lastPushedAt
        );

        $project->updateCreatedAt($this->createdAt);
        $project->updateUpdatedAt($this->updatedAt);

        return $project;
    }
}
