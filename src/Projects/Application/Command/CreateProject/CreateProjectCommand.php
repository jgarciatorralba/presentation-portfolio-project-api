<?php

declare(strict_types=1);

namespace App\Projects\Application\Command\CreateProject;

use App\Shared\Domain\Bus\Command\Command;

final class CreateProjectCommand implements Command
{
    /**
     * @param string[]|null $topics
     */
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly ?string $description,
        private readonly ?array $topics,
        private readonly string $repository,
        private readonly ?string $homepage,
        private readonly bool $archived,
        private readonly \DateTimeImmutable $lastPushedAt
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
