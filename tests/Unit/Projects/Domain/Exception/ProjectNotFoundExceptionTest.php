<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Exception;

use App\Projects\Domain\Exception\ProjectNotFoundException;
use App\Tests\Builder\Projects\Domain\ValueObject\ProjectIdBuilder;
use PHPUnit\Framework\TestCase;

final class ProjectNotFoundExceptionTest extends TestCase
{
    public function testExceptionIsCreated(): void
    {
        $id = ProjectIdBuilder::any()->build();
        $exception = new ProjectNotFoundException($id);

        $this->assertEquals('project_not_found', $exception->errorCode());
        $this->assertEquals(
            sprintf(
                "Project with id '%s' could not be found.",
                $id->value()
            ),
            $exception->errorMessage()
        );
    }
}
