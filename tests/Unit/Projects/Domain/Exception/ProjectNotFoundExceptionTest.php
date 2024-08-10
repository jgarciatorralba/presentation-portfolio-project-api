<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\Domain\Exception;

use App\Projects\Domain\Exception\ProjectNotFoundException;
use App\Shared\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

final class ProjectNotFoundExceptionTest extends TestCase
{
    public function testExceptionIsCreated(): void
    {
        $uuid = Uuid::random();
        $exception = new ProjectNotFoundException($uuid);

        $this->assertEquals('project_not_found', $exception->errorCode());
        $this->assertEquals(
            sprintf(
                "Project with id '%s' could not be found.",
                $uuid->value()
            ),
            $exception->errorMessage()
        );
    }
}
