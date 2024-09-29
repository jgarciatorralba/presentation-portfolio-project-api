<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Shared\Domain\Contract\Comparable;

final readonly class ProjectUrls implements Comparable
{
    private function __construct(
        private string $repository,
        private ?string $homepage,
    ) {
    }

    public static function create(
        string $repository,
        ?string $homepage,
    ): self {
        return new self(
            repository: $repository,
            homepage: $homepage,
        );
    }

    public function repository(): string
    {
        return $this->repository;
    }

    public function homepage(): ?string
    {
        return $this->homepage;
    }

    public function equals(Comparable $projectUrls): bool
    {
        if (!$projectUrls instanceof self) {
            return false;
        }

        return $this->repository === $projectUrls->repository()
            && $this->homepage === $projectUrls->homepage();
    }
}
