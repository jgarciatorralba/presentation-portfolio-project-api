<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Exception;

use App\Projects\Domain\Exception\ProjectAlreadyExistsException;
use App\Tests\Builder\Projects\Domain\ValueObject\ProjectIdBuilder;
use PHPUnit\Framework\TestCase;

final class ProjectAlreadyExistsExceptionTest extends TestCase
{
    public function testExceptionIsCreated(): void
    {
        $projectId = ProjectIdBuilder::any()->build();
        $exception = new ProjectAlreadyExistsException($projectId);

        $this->assertEquals('project_already_exists', $exception->errorCode());
        $this->assertEquals(
            sprintf(
                "Project with id '%s' already exists.",
                $projectId->value()
            ),
            $exception->errorMessage()
        );
    }
}
