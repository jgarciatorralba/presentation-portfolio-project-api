<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\TestCase;

use App\Projects\Domain\Project;
use App\Projects\Domain\Service\CreateProject;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;

final class CreateProjectMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return CreateProject::class;
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     */
    public function shouldCreateProject(Project $expected): void
    {
        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->callback(
                function (Project $actual) use ($expected): true {
                    $this->assertProjectsAreEqual($expected, $actual);
                    return true;
                }
            ));
    }

    /** @throws ExpectationFailedException */
    private function assertProjectsAreEqual(
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
