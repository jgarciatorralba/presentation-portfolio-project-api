<?php

declare(strict_types=1);

namespace Tests\Support\Builder\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\InvalidCodeRepositoryUrlException;
use App\Shared\Domain\ValueObject\GitHubCodeRepository;
use Tests\Support\Builder\BuilderInterface;
use Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class GitHubCodeRepositoryBuilder implements BuilderInterface
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
     * @throws InvalidCodeRepositoryUrlException
     * @throws \InvalidArgumentException
     */
    public function build(): GitHubCodeRepository
    {
        return GitHubCodeRepository::fromUrlValue($this->value);
    }
}
