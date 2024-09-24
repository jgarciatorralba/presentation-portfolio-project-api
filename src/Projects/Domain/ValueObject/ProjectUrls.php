<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

final class ProjectUrls
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

    public function updateRepository(string $repository): void
    {
        $this->repository = $repository;
    }

    public function homepage(): ?string
    {
        return $this->homepage;
    }

    public function updateHomepage(?string $homepage): void
    {
        $this->homepage = $homepage;
    }
}
