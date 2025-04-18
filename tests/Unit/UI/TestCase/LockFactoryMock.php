<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\SharedLockInterface;

final class LockFactoryMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return LockFactory::class;
    }

    public function shouldCreateLock(SharedLockInterface $lock): void
    {
        $this->mock
            ->expects($this->once())
            ->method('createLock')
            ->willReturn($lock);
    }
}
