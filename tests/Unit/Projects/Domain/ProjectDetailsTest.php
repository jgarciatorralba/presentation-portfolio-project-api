<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain;

use App\Projects\Domain\ProjectDetails;
use App\Tests\Builder\Projects\Domain\ProjectDetailsBuilder;
use PHPUnit\Framework\TestCase;

class ProjectDetailsTest extends TestCase
{
    public function testProjectDetailsAreCreated(): void
    {
        $expected = ProjectDetailsBuilder::any()->build();

        $actual = ProjectDetails::create(
            name: $expected->name(),
            description: $expected->description(),
            topics: $expected->topics()
        );

        $this->assertEquals($expected, $expected);
    }
}
