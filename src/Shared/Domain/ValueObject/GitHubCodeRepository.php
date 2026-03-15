<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final readonly class GitHubCodeRepository extends CodeRepository
{
    public function domain(): string
	{
		return 'github.com';
	}
}
