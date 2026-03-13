<?php

declare(strict_types=1);

namespace App\Projects\Domain;

use App\Projects\Domain\Bus\Event\ProjectAddedEvent;
use App\Projects\Domain\Bus\Event\ProjectModifiedEvent;
use App\Projects\Domain\Bus\Event\ProjectRemovedEvent;
use App\Projects\Domain\ValueObject\GitHubCodeRepository;
use App\Projects\Domain\ValueObject\ProjectDetails;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Contract\Comparable;
use App\Shared\Domain\Trait\Timestampable;
use App\Shared\Domain\ValueObject\Url;
use App\Shared\Utils;

class Project extends AggregateRoot implements Comparable
{
    use Timestampable;

    private function __construct(
        private readonly ProjectId $id,
        private ProjectDetails $details,
        private GitHubCodeRepository $repository,
        private ?Url $homepage,
        private bool $archived,
        private \DateTimeImmutable $lastPushedAt
    ) {
        $now = new \DateTimeImmutable();

        $this->updateCreatedAt($now);
        $this->updateUpdatedAt($now);
    }

    public static function create(
        ProjectId $id,
        ProjectDetails $details,
        GitHubCodeRepository $repository,
        ?Url $homepage,
        bool $archived,
        \DateTimeImmutable $lastPushedAt
    ): self {
        return new self(
            id: $id,
            details: $details,
            repository: $repository,
            homepage: $homepage,
            archived: $archived,
            lastPushedAt: $lastPushedAt
        );
    }

    public function id(): ProjectId
    {
        return $this->id;
    }

    public function details(): ProjectDetails
    {
        return $this->details;
    }

    public function repository(): GitHubCodeRepository
    {
        return $this->repository;
    }

    public function homepage(): ?Url
    {
        return $this->homepage;
    }

    public function archived(): bool
    {
        return $this->archived;
    }

    public function lastPushedAt(): \DateTimeImmutable
    {
        return $this->lastPushedAt;
    }

    /** @throws \InvalidArgumentException */
    public function record(): void
    {
        $this->recordEvent(new ProjectAddedEvent($this));
    }

    /** @throws \InvalidArgumentException */
    public function synchronizeWith(Project $project): void
    {
        if (!$this->id()->equals($project->id())) {
            throw new \InvalidArgumentException('Projects must have the same ID to be synchronized');
        }

        $this->details = $project->details;
        $this->repository = $project->repository;
        $this->homepage = $project->homepage;
        $this->archived = $project->archived;
        $this->lastPushedAt = $project->lastPushedAt;

        $this->recordEvent(new ProjectModifiedEvent($this->id()));
    }

    /** @throws \InvalidArgumentException */
    public function erase(): void
    {
        $this->recordEvent(new ProjectRemovedEvent($this->id()));
    }

    /**
     * @return array{
     *    id: int,
     *    name: string,
     *    description: string|null,
     *    topics: list<string>|null,
     *    repository: string,
     *    homepage: string|null,
     *    archived: bool,
     *    lastPushedAt: string
     * }
     */
    #[\Override]
    public function toArray(): array
    {
        return [
            'id' => $this->id()->value(),
            'name' => $this->details()->name(),
            'description' => $this->details()->description(),
            'topics' => $this->details()->topics(),
            'repository' => $this->repository()->urlValue(),
            'homepage' => $this->homepage()?->value(),
            'archived' => $this->archived(),
            'lastPushedAt' => Utils::dateToUTCString($this->lastPushedAt()),
        ];
    }

    #[\Override]
    public function equals(Comparable $project): bool
    {
        if (!$project instanceof self) {
            return false;
        }

        return $this->id()->equals($project->id())
            && $this->details()->equals($project->details())
            && $this->repository()->equals($project->repository())
            && $this->homepage()?->value() === $project->homepage()?->value()
            && $this->archived() === $project->archived()
            && $this->lastPushedAt()->getTimestamp() === $project->lastPushedAt()->getTimestamp();
    }
}
