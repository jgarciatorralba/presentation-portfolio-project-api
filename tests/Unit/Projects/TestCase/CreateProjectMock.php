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

    public function shouldCreateProject(Project $expectedProject): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->callback(function (Project $actualProject) use ($expectedProject) {
                $this->assertGreaterThanOrEqual($expectedProject->createdAt(), $actualProject->createdAt());
                $this->assertGreaterThanOrEqual($expectedProject->updatedAt(), $actualProject->updatedAt());

                $now = new \DateTimeImmutable();
                $this->assertLessThanOrEqual($now, $actualProject->createdAt());
                $this->assertLessThanOrEqual($now, $actualProject->updatedAt());

                $this->assertProjectsAreEqual($expectedProject, $actualProject);

                return true;
            }));
    }

    private function assertProjectsAreEqual(Project $expectedProject, Project $actualProject): void
    {
        $this->assertEquals($expectedProject->id(), $actualProject->id());
        $this->assertEquals($expectedProject->details()->name(), $actualProject->details()->name());
        $this->assertEquals($expectedProject->details()->description(), $actualProject->details()->description());
        $this->assertEquals($expectedProject->details()->topics(), $actualProject->details()->topics());
        $this->assertEquals($expectedProject->urls()->repository(), $actualProject->urls()->repository());
        $this->assertEquals($expectedProject->urls()->homepage(), $actualProject->urls()->homepage());
        $this->assertEquals($expectedProject->archived(), $actualProject->archived());
        $this->assertEquals($expectedProject->lastPushedAt(), $actualProject->lastPushedAt());
    }
}
