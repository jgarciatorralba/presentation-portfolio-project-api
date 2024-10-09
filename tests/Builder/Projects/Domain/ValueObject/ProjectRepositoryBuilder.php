<?php

declare(strict_types=1);

namespace App\Tests\Builder\Projects\Domain\ValueObject;

use App\Projects\Domain\ValueObject\ProjectRepository;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class ProjectRepositoryBuilder implements BuilderInterface
{
    private function __construct(
        private string $value
    ) {
    }

    public static function any(): self
    {
        return new self(value: 'https://github.com/' . FakeValueGenerator::string());
    }

    public function withValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function build(): ProjectRepository
    {
        return ProjectRepository::fromString($this->value);
    }
}
