<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Factory;

use App\Projects\Domain\ProjectDetails;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;

final class ProjectDetailsFactory
{
    /** @param string[] $topics */
    public static function create(
        string $name = null,
        ?string $description = null,
        ?array $topics = null,
    ): ProjectDetails {
        return ProjectDetails::create(
            name: $name ?? FakeValueGenerator::text(),
            description: $description ?? FakeValueGenerator::randomElement([null, FakeValueGenerator::text()]),
            topics: $topics ?? FakeValueGenerator::randomElement([null, self::generateTopics()])
        );
    }

    /** @return string[] */
    private static function generateTopics(): array
    {
        $topics = [];
        $topicsCount = FakeValueGenerator::integer(1, 10);

        for ($i = 0; $i < $topicsCount; $i++) {
            $topics[] = FakeValueGenerator::string();
        }

        return $topics;
    }
}
