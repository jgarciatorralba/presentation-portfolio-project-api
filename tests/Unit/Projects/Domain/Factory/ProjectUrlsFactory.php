<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Factory;

use App\Projects\Domain\ProjectUrls;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;

final class ProjectUrlsFactory
{
    public static function create(
        string $repository = null,
        ?string $homepage = null,
    ): ProjectUrls {
        return ProjectUrls::create(
            repository: $repository ?? ('https://github.com/' . FakeValueGenerator::string()),
            homepage: $homepage ?? FakeValueGenerator::randomElement([
                null,
                FakeValueGenerator::url(),
            ]),
        );
    }
}
