<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\ValueObject;

use App\Projects\Domain\ValueObject\ProjectUrls;
use App\Tests\Builder\Projects\Domain\ValueObject\ProjectUrlsBuilder;
use PHPUnit\Framework\TestCase;

class ProjectUrlsTest extends TestCase
{
    public function testProjectUrlsAreCreated(): void
    {
        $expected = ProjectUrlsBuilder::any()->build();

        $actual = ProjectUrls::create(
            repository: $expected->repository(),
            homepage: $expected->homepage()
        );

        $this->assertEquals($expected, $actual);
    }
}
