<?php

declare(strict_types=1);

namespace App\Tests\Builder\Projects\Domain\ValueObject;

use App\Projects\Domain\ValueObject\ProjectUrls;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class ProjectUrlsBuilder implements BuilderInterface
{
    private function __construct(
        private string $repository,
        private ?string $homepage,
    ) {
    }

    public static function any(): self
    {
        return new self(
            repository: ('https://github.com/' . FakeValueGenerator::string()),
            homepage: FakeValueGenerator::randomElement([null, FakeValueGenerator::url()]),
        );
    }

    public function withRepository(string $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    public function withHomepage(?string $homepage): self
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
