<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\ValueObject;

use App\Projects\Domain\ValueObject\ProjectId;
use App\Tests\Builder\Projects\Domain\ValueObject\ProjectIdBuilder;
use PHPUnit\Framework\TestCase;

final class ProjectIdTest extends TestCase
{
    public function testProjectIdIsCreated(): void
    {
        $expected = ProjectIdBuilder::any()->build();

        $actual = ProjectId::create(
            value: $expected->value()
        );

        $this->assertEquals($expected, $actual);
    }
}
