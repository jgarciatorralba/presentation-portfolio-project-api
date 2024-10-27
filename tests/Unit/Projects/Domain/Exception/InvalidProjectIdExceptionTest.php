<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Exception;

use App\Projects\Domain\Exception\InvalidProjectIdException;
use PHPUnit\Framework\TestCase;

final class InvalidProjectIdExceptionTest extends TestCase
{
    public function testExceptionIsCreated(): void
    {
        $idValue = -1;
        $exception = new InvalidProjectIdException($idValue);

        $this->assertEquals('invalid_project_id', $exception->errorCode());
        $this->assertEquals(
            sprintf(
                "Invalid value for project id: '%s'. Must be a positive integer.",
                $idValue
            ),
            $exception->errorMessage()
        );
    }
}
