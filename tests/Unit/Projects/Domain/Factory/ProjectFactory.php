<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Factory;

use App\Projects\Domain\Project;
use App\Projects\Domain\ProjectDetails;
use App\Projects\Domain\ProjectUrls;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class ProjectFactory
{
    public static function create(
        int $id = null,
        ProjectDetails $details = null,
        ProjectUrls $urls = null,
        bool $archived = null,
        \DateTimeImmutable $lastPushedAt = null
    ): Project {
        return Project::create(
            id: $id ?? FakeValueGenerator::integer(),
            details: $details ?? ProjectDetailsFactory::create(),
            urls: $urls ?? ProjectUrlsFactory::create(),
            archived: $archived ?? FakeValueGenerator::boolean(),
            lastPushedAt: $lastPushedAt ?? FakeValueGenerator::dateTime()
        );
    }

    /**
     * @return Project[]
     */
    public static function createMany(?int $amount = null): array
    {
        if ($amount === null) {
            $amount = FakeValueGenerator::integer(max: 100);
        }

        $projects = [];

        $i = 0;
        while ($i < $amount) {
            $project = self::create();
            if (!in_array($project->id(), array_keys($projects))) {
                $projects[$project->id()] = $project;
                $i++;
            }
        }

        return array_values($projects);
    }
}
