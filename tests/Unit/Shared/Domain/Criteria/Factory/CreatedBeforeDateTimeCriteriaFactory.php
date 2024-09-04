<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria\Factory;

use App\Shared\Domain\Criteria\CreatedBeforeDateTimeCriteria;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class CreatedBeforeDateTimeCriteriaFactory
{
    public static function create(
        ?\DateTimeImmutable $maxCreatedAt = null,
        ?int $limit = null
    ): CreatedBeforeDateTimeCriteria {
        return new CreatedBeforeDateTimeCriteria(
            $maxCreatedAt ?? FakeValueGenerator::dateTime(),
            $limit ?? FakeValueGenerator::randomElement([null, FakeValueGenerator::integer()])
        );
    }
}
