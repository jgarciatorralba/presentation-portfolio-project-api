<?php

declare(strict_types=1);

namespace Tests\Support\Builder\Shared\Domain\Criteria;

use App\Shared\Domain\Criteria\PushedBeforeDateTimeCriteria;
use Tests\Support\Builder\BuilderInterface;
use Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class PushedBeforeDateTimeCriteriaBuilder implements BuilderInterface
{
    private function __construct(
        private \DateTimeImmutable $maxPushedAt,
        private ?int $limit
    ) {
    }

    public static function any(): self
    {
        return new self(
            maxPushedAt: FakeValueGenerator::dateTime(),
            limit: FakeValueGenerator::randomElement([
                null,
                FakeValueGenerator::integer()
            ])
        );
    }

    public function build(): PushedBeforeDateTimeCriteria
    {
        return new PushedBeforeDateTimeCriteria(
            $this->maxPushedAt,
            $this->limit
        );
    }

    public function withMaxPushedAt(\DateTimeImmutable $maxPushedAt): self
    {
        $this->maxPushedAt = $maxPushedAt;

        return $this;
    }

    public function withLimit(?int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }
}
