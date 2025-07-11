<?php

declare(strict_types=1);

namespace App\Tests\Support\Builder\Projects\Domain\ValueObject;

use App\Projects\Domain\ValueObject\ProjectId;
use App\Tests\Support\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class ProjectIdBuilder implements BuilderInterface
{
    private function __construct(
        private int $value
    ) {
    }

    public static function any(): self
    {
        return new self(value: FakeValueGenerator::integer(min: 1));
    }

    public function withValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    /** @throws \InvalidArgumentException */
    public function build(): ProjectId
    {
        return ProjectId::create($this->value);
    }
}
