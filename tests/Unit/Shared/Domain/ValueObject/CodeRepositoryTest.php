<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\CodeRepository;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Shared\Domain\Testing\EmptyDomainCodeRepository;
use Tests\Unit\Shared\Domain\Testing\InvalidDomainCodeRepository;

final class CodeRepositoryTest extends TestCase
{
    public function testItThrowsExceptionWhenDomainIsEmpty(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Domain cannot be empty.');

		EmptyDomainCodeRepository::fromUrlValue(url: 'https://github.com');
	}

	public function testItThrowsExceptionWhenDomainIsInvalid(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid domain: invalid-#-domain');

		InvalidDomainCodeRepository::fromUrlValue(url: 'https://github.com');
	}
}
