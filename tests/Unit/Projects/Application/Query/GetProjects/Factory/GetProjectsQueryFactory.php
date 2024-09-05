<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Application\Query\GetProjects\Factory;

use App\Projects\Application\Query\GetProjects\GetProjectsQuery;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class GetProjectsQueryFactory
{
    public static function create(
        ?int $pageSize = null,
        ?\DateTimeImmutable $maxCreatedAt = null
    ): GetProjectsQuery {
        return new GetProjectsQuery(
            $pageSize ?? FakeValueGenerator::randomElement([null, FakeValueGenerator::integer()]),
            $maxCreatedAt ?? FakeValueGenerator::randomElement([null, FakeValueGenerator::dateTime()])
        );
    }
}
