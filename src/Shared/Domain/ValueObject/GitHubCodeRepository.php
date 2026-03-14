<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final readonly class GitHubCodeRepository extends CodeRepository
{
    protected const string DOMAIN = 'github.com';
}
