<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Shared\Domain\Contract\Comparable;
use App\Shared\Domain\ValueObject\Url;

final readonly class ProjectUrls implements Comparable
{
    private function __construct(
        private ProjectRepositoryUrl $repository,
        private ?Url $homepage,
    ) {
    }

    public static function create(
        ProjectRepositoryUrl $repository,
        ?Url $homepage,
    ): self {
        return new self(
            repository: $repository,
            homepage: $homepage,
        );
    }

    public function repository(): ProjectRepositoryUrl
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
