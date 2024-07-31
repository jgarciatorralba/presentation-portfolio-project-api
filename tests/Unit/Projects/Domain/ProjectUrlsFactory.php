<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain;

use App\Projects\Domain\ProjectUrls;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;

final class ProjectUrlsFactory
{
    public static function create(
        string $repository = null,
        ?string $homepage = null,
    ): ProjectUrls {
        return ProjectUrls::create(
            $repository ?? FakeValueGenerator::text(),
            $homepage ?? FakeValueGenerator::randomElement([null, FakeValueGenerator::text()]),
        );
    }
}
