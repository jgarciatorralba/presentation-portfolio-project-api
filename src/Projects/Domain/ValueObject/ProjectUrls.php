<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Shared\Domain\Contract\Comparable;
use App\Shared\Domain\ValueObject\Url;

final readonly class ProjectUrls implements Comparable
{
    private function __construct(
        private ProjectRepository $repository,
        private ?Url $homepage,
    ) {
    }

    public static function create(
        ProjectRepository $repository,
        ?Url $homepage,
    ): self {
        return new self(
            repository: $repository,
            homepage: $homepage,
        );
    }

    public function repository(): ProjectRepository
    {
        return $this->repository;
    }

    public function homepage(): ?Url
    {
        return $this->homepage;
    }

    public function equals(Comparable $projectUrls): bool
    {
        if (!$projectUrls instanceof self) {
            return false;
        }

        return $this->repository->value() === $projectUrls->repository()->value()
            && $this->homepage?->value() === $projectUrls->homepage()?->value();
    }
}
