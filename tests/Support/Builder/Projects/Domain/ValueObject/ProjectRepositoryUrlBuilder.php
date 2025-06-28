<?php

declare(strict_types=1);

namespace App\Tests\Support\Builder\Projects\Domain\ValueObject;

use App\Projects\Domain\Exception\InvalidProjectRepositoryUrlException;
use App\Projects\Domain\ValueObject\ProjectRepositoryUrl;
use App\Tests\Support\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class ProjectRepositoryUrlBuilder implements BuilderInterface
{
    private const string GITHUB_DOMAIN = 'https://github.com/';

    private function __construct(
        private string $value
    ) {
    }

    public static function any(): self
    {
        return new self(value: self::GITHUB_DOMAIN . FakeValueGenerator::string());
    }

    public function withValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @throws InvalidProjectRepositoryUrlException
     * @throws \InvalidArgumentException
     */
    public function build(): ProjectRepositoryUrl
    {
        return ProjectRepositoryUrl::fromString($this->value);
    }
}
