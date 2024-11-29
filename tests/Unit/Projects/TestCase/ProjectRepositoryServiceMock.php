<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\TestCase;

use App\Projects\Domain\Project;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\ExpectationFailedException;

abstract class ProjectRepositoryServiceMock extends AbstractMock
{
    /** @throws ExpectationFailedException */
    protected function assertProjectsAreEqual(
        Project $expected,
        Project $actual
    ): void {
        $this->assertEquals(
            $expected->id(),
            $actual->id()
        );
        $this->assertEquals(
            $expected->details(),
            $actual->details()
        );
        $this->assertEquals(
            $expected->urls(),
            $actual->urls()
        );
        $this->assertEquals(
            $expected->archived(),
            $actual->archived()
        );
        $this->assertEquals(
            $expected->lastPushedAt(),
            $actual->lastPushedAt()
        );
        $this->assertEquals(
            $expected->deletedAt(),
            $actual->deletedAt()
        );

        $diffCreatedAt = $actual->createdAt()->getTimestamp()
            - $expected->createdAt()->getTimestamp();
        $this->assertLessThanOrEqual(1, $diffCreatedAt);

        $diffUpdatedAt = $actual->updatedAt()->getTimestamp()
            - $expected->updatedAt()->getTimestamp();
        $this->assertLessThanOrEqual(1, $diffUpdatedAt);
    }
}
