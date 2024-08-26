<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Exception;

use App\Projects\Domain\Exception\ProjectAlreadyExistsException;
use App\Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;
use PHPUnit\Framework\TestCase;

final class ProjectAlreadyExistsExceptionTest extends TestCase
{
    public function testExceptionIsCreated(): void
    {
        $id = FakeValueGenerator::integer();
        $exception = new ProjectAlreadyExistsException($id);

        $this->assertEquals('project_already_exists', $exception->errorCode());
        $this->assertEquals(
            sprintf(
                "Project with id '%s' already exists.",
                $id
            ),
            $exception->errorMessage()
        );
    }
}
