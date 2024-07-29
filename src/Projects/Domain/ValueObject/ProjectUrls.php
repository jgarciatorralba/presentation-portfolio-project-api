<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

final class ProjectUrls
{
    private function __construct(
        private string $repoUrl,
        private ?string $homepage,
    ) {
    }

    public static function create(
        string $repoUrl,
        ?string $homepage,
    ): self {
        return new self(
            repoUrl: $repoUrl,
            homepage: $homepage,
        );
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
}
