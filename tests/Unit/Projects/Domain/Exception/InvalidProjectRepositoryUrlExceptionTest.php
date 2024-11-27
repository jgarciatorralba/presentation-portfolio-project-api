<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Exception;

use App\Projects\Domain\Exception\InvalidProjectRepositoryUrlException;
use App\Tests\Builder\Projects\Domain\ValueObject\ProjectIdBuilder;
use PHPUnit\Framework\TestCase;

final class InvalidProjectRepositoryUrlExceptionTest extends TestCase
{
    public function testItIsCreated(): void
    {
        $urlString = 'invalid-url';
        $exception = new InvalidProjectRepositoryUrlException($urlString);

        $this->assertEquals('invalid_project_repository', $exception->errorCode());
        $this->assertEquals(
            sprintf(
                "Invalid value for project repository: '%s'. Must belong to GitHub domain.",
                $urlString
            ),
            $exception->errorMessage()
        );
    }
}
