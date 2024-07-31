<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain;

use App\Projects\Domain\Project;
use App\Projects\Domain\ProjectDetails;
use App\Projects\Domain\ProjectUrls;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use DateTimeImmutable;

final class ProjectFactory
{
    public static function create(
        int $id = null,
        ProjectDetails $details = null,
        ProjectUrls $urls = null,
        bool $archived = null,
        DateTimeImmutable $lastPushed = null,
        DateTimeImmutable $createdAt = null,
        DateTimeImmutable $updatedAt = null
    ): Project {
        return Project::create(
            $id ?? FakeValueGenerator::integer(),
            $details ?? ProjectDetailsFactory::create(),
            $urls ?? ProjectUrlsFactory::create(),
            $archived ?? FakeValueGenerator::boolean(),
            $lastPushed ?? FakeValueGenerator::dateTime(),
            $createdAt ?? FakeValueGenerator::dateTime(),
            $updatedAt ?? FakeValueGenerator::dateTime()
        );
    }
}
