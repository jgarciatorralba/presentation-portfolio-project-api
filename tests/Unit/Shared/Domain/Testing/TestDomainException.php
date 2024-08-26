<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Testing;

use App\Shared\Domain\DomainException;

final class TestDomainException extends DomainException
{
    public function errorCode(): string
    {
        return 'test_domain';
    }

    public function errorMessage(): string
    {
        return 'Test error message';
    }
}
