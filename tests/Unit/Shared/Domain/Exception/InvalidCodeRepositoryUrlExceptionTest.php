<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\Exception;

use App\Shared\Domain\Exception\InvalidCodeRepositoryUrlException;
use PHPUnit\Framework\TestCase;

final class InvalidCodeRepositoryUrlExceptionTest extends TestCase
{
    public function testItIsCreated(): void
    {
        $urlString = 'invalid-url';
        $domain = 'GitHub';
        $exception = new InvalidCodeRepositoryUrlException(
            url: $urlString,
            domain: $domain
        );

        $this->assertEquals('invalid_code_repository', $exception->errorCode());
        $this->assertEquals(
            sprintf(
                "Invalid value for code repository: '%s'. Must belong to %s domain.",
                $urlString,
                $domain
            ),
            $exception->errorMessage()
        );
    }
}
