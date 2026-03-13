<?php

declare(strict_types=1);

namespace Tests\Support\Builder\Projects\Domain;

use App\Projects\Domain\Exception\InvalidCodeRepositoryUrlException;
use App\Projects\Domain\Project;
use App\Projects\Domain\ValueObject\GitHubCodeRepository;
use App\Projects\Domain\ValueObject\ProjectDetails;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Shared\Domain\ValueObject\Url;
use Tests\Support\Builder\BuilderInterface;
use Tests\Support\Builder\Projects\Domain\ValueObject\GitHubCodeRepositoryBuilder;
use Tests\Support\Builder\Projects\Domain\ValueObject\ProjectDetailsBuilder;
use Tests\Support\Builder\Projects\Domain\ValueObject\ProjectIdBuilder;
use Tests\Support\Builder\Shared\Domain\ValueObject\UrlBuilder;
use Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class ProjectBuilder implements BuilderInterface
{
    private function __construct(
        private ProjectId $id,
        private ProjectDetails $details,
        private GitHubCodeRepository $repository,
        private ?Url $homepage,
        private bool $archived,
        private \DateTimeImmutable $lastPushedAt,
        private \DateTimeImmutable $createdAt,
        private \DateTimeImmutable $updatedAt
    ) {
    }

    /**
     * @throws InvalidCodeRepositoryUrlException
     * @throws \InvalidArgumentException
     */
    public static function any(): self
    {
        return new self(
            id: ProjectIdBuilder::any()->build(),
            details: ProjectDetailsBuilder::any()->build(),
            repository: GitHubCodeRepositoryBuilder::any()->build(),
            homepage: UrlBuilder::any()->build(),
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
        $project = Project::create(
            id: $this->id,
            details: $this->details,
            repository: $this->repository,
            homepage: $this->homepage,
            archived: $this->archived,
            lastPushedAt: $this->lastPushedAt
        );

        $project->updateCreatedAt($this->createdAt);
        $project->updateUpdatedAt($this->updatedAt);

        return $project;
    }
}
