<?php

declare(strict_types=1);

namespace App\Tests\Builder\Projects\Domain\ValueObject;

use App\Projects\Domain\Exception\InvalidProjectRepositoryUrlException;
use App\Projects\Domain\ValueObject\ProjectRepositoryUrl;
use App\Projects\Domain\ValueObject\ProjectUrls;
use App\Shared\Domain\ValueObject\Url;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Builder\Shared\Domain\ValueObject\UrlBuilder;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class ProjectUrlsBuilder implements BuilderInterface
{
    private function __construct(
        private ProjectRepositoryUrl $repository,
        private ?Url $homepage,
    ) {
    }

    /**
     * @throws InvalidProjectRepositoryUrlException
     * @throws \InvalidArgumentException
     */
    public static function any(): self
    {
        return new self(
            repository: ProjectRepositoryUrlBuilder::any()->build(),
            homepage: FakeValueGenerator::randomElement([
                null,
                UrlBuilder::any()->build(),
            ]),
        );
    }

    public function withRepository(ProjectRepositoryUrl $repository): self
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
