<?php

declare(strict_types=1);

namespace App\Tests\Support\Builder\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\Url;
use App\Tests\Support\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class UrlBuilder implements BuilderInterface
{
    private function __construct(
        private string $value
    ) {
    }

    public static function any(): self
    {
        return new self(value: FakeValueGenerator::url());
    }

    public function withValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /** @throws \InvalidArgumentException */
    public function build(): Url
    {
        return Url::fromString($this->value);
    }
}
