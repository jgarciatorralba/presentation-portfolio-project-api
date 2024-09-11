<?php

declare(strict_types=1);

namespace App\Projects\Domain;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Trait\TimestampableTrait;
use App\Shared\Utils;

class Project extends AggregateRoot
{
    use TimestampableTrait;

    private function __construct(
        private int $id,
        private ProjectDetails $details,
        private ProjectUrls $urls,
        private bool $archived,
        private \DateTimeImmutable $lastPushedAt
    ) {
        $now = new \DateTimeImmutable();

        $this->updateCreatedAt($now);
        $this->updateUpdatedAt($now);
    }

    public static function create(
        int $id,
        ProjectDetails $details,
        ProjectUrls $urls,
        bool $archived,
        \DateTimeImmutable $lastPushedAt
    ): self {
        return new self(
            id: $id,
            details: $details,
            urls: $urls,
            archived: $archived,
            lastPushedAt: $lastPushedAt
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

    public function lastPushedAt(): \DateTimeImmutable
    {
        return $this->lastPushedAt;
    }

    public function updateLastPushedAt(\DateTimeImmutable $lastPushedAt): void
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
     *    lastPushedAt: string
     *  }
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->details()->name(),
            'description' => $this->details()->description(),
            'topics' => !empty($this->details()->topics())
                ? $this->details()->topics()
                : null,
            'repository' => $this->urls()->repository(),
            'homepage' => $this->urls()->homepage(),
            'archived' => $this->archived,
            'lastPushedAt' => Utils::dateToString($this->lastPushedAt)
        ];
    }
}
