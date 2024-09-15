<?php

declare(strict_types=1);

namespace App\Tests\Builder\Shared\Domain\Criteria;

use App\Shared\Domain\Criteria\UpdatedBeforeDateTimeCriteria;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class UpdatedBeforeDateTimeCriteriaBuilder implements BuilderInterface
{
    private function __construct(
        private \DateTimeImmutable $maxUpdatedAt,
        private ?int $limit
    ) {
    }

    public static function any(): self
    {
        return new self(
            maxUpdatedAt: FakeValueGenerator::dateTime(),
            limit: FakeValueGenerator::randomElement([
                null,
                FakeValueGenerator::integer()
            ])
        );
    }

    public function build(): UpdatedBeforeDateTimeCriteria
    {
        return new UpdatedBeforeDateTimeCriteria(
            $this->maxUpdatedAt,
            $this->limit
        );
    }

    public function withMaxUpdatedAt(\DateTimeImmutable $maxUpdatedAt): self
    {
        $this->maxUpdatedAt = $maxUpdatedAt;

        return $this;
    }

    public function withLimit(?int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }
}
