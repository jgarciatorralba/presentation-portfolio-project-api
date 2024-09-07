<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use App\Tests\Unit\Shared\Infrastructure\Testing\DoctrineTestCollection;
use Doctrine\Common\Collections\Criteria;
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

    /**
     * @param object[] $entities
     */
    public function shouldFindEntitiesMatchingCriteria(
        Criteria $criteria,
        array $entities
    ): void {
        $this->mock
            ->expects($this->once())
            ->method('matching')
            ->with($criteria)
            ->willReturnCallback(function () use ($entities) {
                return new DoctrineTestCollection($entities);
            });
    }
}
