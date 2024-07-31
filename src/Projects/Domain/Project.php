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
        private DateTimeImmutable $lastPushed,
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
        DateTimeImmutable $lastPushed,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            id: $id,
            details: $details,
            urls: $urls,
            archived: $archived,
            lastPushed: $lastPushed,
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

    public function lastPushed(): DateTimeImmutable
    {
        return $this->lastPushed;
    }

    public function updateLastPushed(DateTimeImmutable $lastPushed): void
    {
        $this->lastPushed = $lastPushed;
    }

    /** @return array{
     *    id: int,
     *    name: string,
     *    description: string|null,
     *    repository: string,
     *    homepage: string|null,
     *    topics: string[]|null,
     *    archived: bool,
     *    lastPushed: string,
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
            'lastPushed' => Utils::dateToString($this->lastPushed),
            'createdAt' => Utils::dateToString($this->createdAt)
        ];
    }
}
