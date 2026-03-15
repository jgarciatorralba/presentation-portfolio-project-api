<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\Testing;

use App\Shared\Domain\ValueObject\CodeRepository;

final readonly class EmptyDomainCodeRepository extends CodeRepository
{
    public function domain(): string
    {
        return '';
    }
}
