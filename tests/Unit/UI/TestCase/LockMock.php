<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use Symfony\Component\Lock\SharedLockInterface;

final class LockMock extends AbstractMock
{
	protected function getClassName(): string
	{
		return SharedLockInterface::class;
	}

	public function shouldAcquireWithResult(bool $result): void
	{
		$this->mock
			->expects($this->once())
			->method('acquire')
			->willReturn($result);
	}
}
