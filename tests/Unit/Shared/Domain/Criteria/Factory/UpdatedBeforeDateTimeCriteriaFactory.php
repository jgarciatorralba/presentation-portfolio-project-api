<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Criteria\Factory;

use App\Shared\Domain\Criteria\UpdatedBeforeDateTimeCriteria;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class UpdatedBeforeDateTimeCriteriaFactory
{
    public static function create(
        ?\DateTimeImmutable $maxUpdatedAt = null,
        ?int $limit = null
    ): UpdatedBeforeDateTimeCriteria {
        return new UpdatedBeforeDateTimeCriteria(
            $maxUpdatedAt ?? FakeValueGenerator::dateTime(),
            $limit ?? FakeValueGenerator::randomElement([null, FakeValueGenerator::integer()])
        );
    }
}
