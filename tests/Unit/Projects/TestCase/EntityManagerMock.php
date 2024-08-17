<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use Doctrine\ORM\EntityManagerInterface;

final class EntityManagerMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return EntityManagerInterface::class;
    }

    public function shouldPersistEntity(object $entity): void
    {
        $this->mock
            ->expects($this->once())
            ->method('persist')
            ->with($entity);

        $this->mock
            ->expects($this->once())
            ->method('flush');
    }

    public function shouldUpdateEntity(): void
    {
        $this->mock
            ->expects($this->once())
            ->method('flush');
    }

    public function shouldRemoveEntity(object $entity): void
    {
        $this->mock
            ->expects($this->once())
            ->method('remove')
            ->with($entity);

        $this->mock
            ->expects($this->once())
            ->method('flush');
    }
}
