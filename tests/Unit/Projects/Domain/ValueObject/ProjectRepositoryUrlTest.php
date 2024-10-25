<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\ValueObject;

use App\Projects\Domain\Exception\InvalidProjectRepositoryUrlException;
use App\Projects\Domain\ValueObject\ProjectRepositoryUrl;
use App\Tests\Builder\Projects\Domain\ValueObject\ProjectRepositoryUrlBuilder;
use PHPUnit\Framework\TestCase;

final class ProjectRepositoryUrlTest extends TestCase
{
    private ?ProjectRepositoryUrl $expected;

    protected function setUp(): void
    {
        $this->expected = ProjectRepositoryUrlBuilder::any()->build();
    }

    protected function tearDown(): void
    {
        $this->expected = null;
    }

    public function testProjectRepositoryUrlIsCreated(): void
    {
        $actual = ProjectRepositoryUrl::fromString(
            value: $this->expected->value()
        );

        $this->assertEquals($this->expected, $actual);
    }
}
