<?php

declare(strict_types=1);

namespace App\Projects\Application\Command\CreateProject;

use App\Shared\Domain\Bus\Command\Command;

final readonly class CreateProjectCommand implements Command
{
    /**
     * @param string[]|null $topics
     */
    public function __construct(
        private int $id,
        private string $name,
        private ?string $description,
        private ?array $topics,
        private string $repository,
        private ?string $homepage,
        private bool $archived,
        private \DateTimeImmutable $lastPushedAt
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    /**
     * @return string[]|null
     */
    public function topics(): ?array
    {
        return $this->topics;
    }

    public function repository(): string
    {
        return $this->repository;
    }

    public function homepage(): ?string
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
}
