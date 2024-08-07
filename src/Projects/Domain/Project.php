<?php

declare(strict_types=1);

namespace App\Projects\Domain;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Trait\TimestampableTrait;
use App\Shared\Utils;
use DateTimeImmutable;

class Project extends AggregateRoot
{
    use TimestampableTrait;

    private function __construct(
        private int $id,
        private ProjectDetails $details,
        private ProjectUrls $urls,
        private bool $archived,
        private DateTimeImmutable $lastPushedAt,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ) {
        $this->updateCreatedAt($createdAt);
        $this->updateUpdatedAt($updatedAt);
    }

    public static function create(
        int $id,
        ProjectDetails $details,
        ProjectUrls $urls,
        bool $archived,
        DateTimeImmutable $lastPushedAt,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            id: $id,
            details: $details,
            urls: $urls,
            archived: $archived,
            lastPushedAt: $lastPushedAt,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }

    public function id(): int
    {
        return $this->id;
    }

    public function details(): ProjectDetails
    {
        return $this->details;
    }

    public function urls(): ProjectUrls
    {
        return $this->urls;
    }

    public function archived(): bool
    {
        return $this->archived;
    }

    public function updateArchived(bool $archived): void
    {
        $this->archived = $archived;
    }

    public function lastPushedAt(): DateTimeImmutable
    {
        return $this->lastPushedAt;
    }

    public function updateLastPushedAt(DateTimeImmutable $lastPushedAt): void
    {
        $this->lastPushedAt = $lastPushedAt;
    }

    /** @return array{
     *    id: int,
     *    name: string,
     *    description: string|null,
     *    repository: string,
     *    homepage: string|null,
     *    topics: string[]|null,
     *    archived: bool,
     *    lastPushedAt: string,
     *    createdAt: string
     *  }
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->details()->name(),
            'description' => $this->details()->description(),
            'topics' => $this->details()->topics(),
            'repository' => $this->urls()->repository(),
            'homepage' => $this->urls()->homepage(),
            'archived' => $this->archived,
            'lastPushedAt' => Utils::dateToString($this->lastPushedAt),
            'createdAt' => Utils::dateToString($this->createdAt)
        ];
    }
}
