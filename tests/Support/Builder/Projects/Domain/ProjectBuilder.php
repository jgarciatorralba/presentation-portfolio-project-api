<?php

declare(strict_types=1);

namespace Tests\Support\Builder\Projects\Domain;

use App\Projects\Domain\Exception\InvalidProjectRepositoryUrlException;
use App\Projects\Domain\Project;
use App\Projects\Domain\ValueObject\ProjectDetails;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Projects\Domain\ValueObject\ProjectUrls;
use Tests\Support\Builder\BuilderInterface;
use Tests\Support\Builder\Projects\Domain\ValueObject\ProjectDetailsBuilder;
use Tests\Support\Builder\Projects\Domain\ValueObject\ProjectIdBuilder;
use Tests\Support\Builder\Projects\Domain\ValueObject\ProjectUrlsBuilder;
use Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class ProjectBuilder implements BuilderInterface
{
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

    public function withId(ProjectId $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function withName(string $name): self
    {
        $details = ProjectDetailsBuilder::any()
            ->withName($name)
            ->build();

        $this->details = $details;

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

    public function withLastPushedAt(\DateTimeImmutable $lastPushedAt): self
    {
        $this->lastPushedAt = $lastPushedAt;

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
