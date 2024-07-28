<?php

declare(strict_types=1);

namespace App\Projects\Domain;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Trait\TimestampableTrait;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Utils;
use DateTimeImmutable;

class Project extends AggregateRoot
{
    use TimestampableTrait;

    /** @param string[] $topics */
    private function __construct(
        private Uuid $id,
        private int $githubId,
        private string $name,
        private ?string $description,
        private string $repoUrl,
        private ?string $homepage,
        private ?array $topics,
        private bool $archived,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ) {
        $this->updateCreatedAt($createdAt);
        $this->updateUpdatedAt($updatedAt);
    }

    /** @param string[] $topics */
    public static function create(
        Uuid $id,
        int $githubId,
        string $name,
        ?string $description,
        string $repoUrl,
        ?string $homepage,
        ?array $topics,
        bool $archived,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            id: $id,
            githubId: $githubId,
            name: $name,
            description: $description,
            repoUrl: $repoUrl,
            homepage: $homepage,
            topics: $topics,
            archived: $archived,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function githubId(): int
    {
        return $this->githubId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function updateName(string $name): void
    {
        $this->name = $name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function updateDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function repoUrl(): string
    {
        return $this->repoUrl;
    }

    public function updateRepoUrl(string $repoUrl): void
    {
        $this->repoUrl = $repoUrl;
    }

    public function homepage(): ?string
    {
        return $this->homepage;
    }

    public function updateHomepage(?string $homepage): void
    {
        $this->homepage = $homepage;
    }

    /** @return string[]|null */
    public function topics(): ?array
    {
        return $this->topics;
    }

    /** @param string[]|null $topics */
    public function updateTopics(?array $topics): void
    {
        $this->topics = $topics;
    }

    public function archived(): bool
    {
        return $this->archived;
    }

    public function updateArchived(bool $archived): void
    {
        $this->archived = $archived;
    }

    /** @return array{
     *    id: string,
     *    githubId: int,
     *    name: string,
     *    description: string|null,
     *    repoUrl: string,
     *    homepage: string|null,
     *    topics: string[]|null,
     *    archived: bool,
     *    createdAt: string
     *  }
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'githubId' => $this->githubId,
            'name' => $this->name,
            'description' => $this->description,
            'repoUrl' => $this->repoUrl,
            'homepage' => $this->homepage,
            'topics' => $this->topics,
            'archived' => $this->archived,
            'createdAt' => Utils::dateToString($this->createdAt)
        ];
    }
}
