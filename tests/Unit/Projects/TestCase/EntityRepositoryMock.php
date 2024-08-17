<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use Doctrine\ORM\EntityRepository;

final class EntityRepositoryMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return EntityRepository::class;
    }

    public function shouldFindEntity(mixed $id, ?object $entity): void
    {
        $entityId = is_object($entity) && method_exists($entity, 'id')
            ? $entity->id()
            : null;

        $this->mock
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($entityId === $id ? $entity : null);
    }
}
