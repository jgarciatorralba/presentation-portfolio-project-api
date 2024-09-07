<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\TestCase;

use App\Projects\Domain\Project;
use App\Projects\Domain\Service\CreateProject;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class CreateProjectMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return CreateProject::class;
    }

    public function shouldCreateProject(Project $expected): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->callback(
                function (Project $actual) use ($expected) {
                    $this->assertGreaterThanOrEqual(
                        $expected->createdAt(),
                        $actual->createdAt()
                    );
                    $this->assertGreaterThanOrEqual(
                        $expected->updatedAt(),
                        $actual->updatedAt()
                    );

                    $now = new \DateTimeImmutable();
                    $this->assertLessThanOrEqual($now, $actual->createdAt());
                    $this->assertLessThanOrEqual($now, $actual->updatedAt());

                    $this->assertProjectsAreEqual($expected, $actual);

                    return true;
                }
            ));
    }

    private function assertProjectsAreEqual(
        Project $expected,
        Project $actual
    ): void {
        $this->assertEquals(
            $expected->id(),
            $actual->id()
        );
        $this->assertEquals(
            $expected->details()->name(),
            $actual->details()->name()
        );
        $this->assertEquals(
            $expected->details()->description(),
            $actual->details()->description()
        );
        $this->assertEquals(
            $expected->details()->topics(),
            $actual->details()->topics()
        );
        $this->assertEquals(
            $expected->urls()->repository(),
            $actual->urls()->repository()
        );
        $this->assertEquals(
            $expected->urls()->homepage(),
            $actual->urls()->homepage()
        );
        $this->assertEquals(
            $expected->archived(),
            $actual->archived()
        );
        $this->assertEquals(
            $expected->lastPushedAt(),
            $actual->lastPushedAt()
        );
    }
}
