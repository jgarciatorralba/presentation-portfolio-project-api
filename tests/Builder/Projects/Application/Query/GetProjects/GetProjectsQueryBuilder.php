<?php

declare(strict_types=1);

namespace App\Tests\Builder\Projects\Application\Query\GetProjects;

use App\Projects\Application\Query\GetProjects\GetProjectsQuery;
use App\Tests\Builder\BuilderInterface;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class GetProjectsQueryBuilder implements BuilderInterface
{
    private function __construct(
        private ?int $pageSize,
        private ?\DateTimeImmutable $maxPushedAt
    ) {
    }

    public static function any(): self
    {
        return new self(
            pageSize: FakeValueGenerator::randomElement([
                null,
                FakeValueGenerator::integer()
            ]),
            maxPushedAt: FakeValueGenerator::randomElement([
                null,
                FakeValueGenerator::dateTime()
            ]),
        );
    }

    public function build(): GetProjectsQuery
    {
        return new GetProjectsQuery(
            pageSize: $this->pageSize,
            maxPushedAt:$this->maxPushedAt
        );
    }
}
