<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

final readonly class GitHubCodeRepository extends CodeRepository
{
    protected const string DOMAIN = 'github.com';
}
