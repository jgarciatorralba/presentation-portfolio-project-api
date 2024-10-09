<?php

declare(strict_types=1);

namespace App\Tests\Builder\Projects\Domain\ValueObject;

use App\Projects\Domain\ValueObject\ProjectRepository;
use App\Projects\Domain\ValueObject\ProjectUrls;
use App\Shared\Domain\ValueObject\Url;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Builder\Shared\Domain\ValueObject\UrlBuilder;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class ProjectUrlsBuilder implements BuilderInterface
{
    private function __construct(
        private ProjectRepository $repository,
        private ?Url $homepage,
    ) {
    }

    public static function any(): self
    {
        return new self(
            repository: ProjectRepositoryBuilder::any()->build(),
            homepage: FakeValueGenerator::randomElement([
                null,
                UrlBuilder::any()->build(),
            ]),
        );
    }

    public function withRepository(ProjectRepository $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    public function withHomepage(?Url $homepage): self
    {
        $this->homepage = $homepage;

        return $this;
    }

    public function build(): ProjectUrls
    {
        return ProjectUrls::create(
            repository: $this->repository,
            homepage: $this->homepage,
        );
    }
}
