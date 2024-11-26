<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use App\Tests\Unit\Shared\Infrastructure\Testing\DoctrineTestCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\IncompatibleReturnValueException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;

final class EntityRepositoryMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return EntityRepository::class;
    }

    /**
     * @throws Exception
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     */
    public function shouldFindEntity(mixed $id, ?object $entity): void
    {
        $entityId = is_object($entity) && method_exists($entity, 'id')
            ? $entity->id()
            : null;

        $idValue = is_object($id) && method_exists($id, 'value')
            ? $id->value()
            : $id;

        $this->mock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => $idValue])
            ->willReturn($entityId === $id ? $entity : null);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     */
    public function shouldFindEntitiesMatchingCriteria(
        Criteria $criteria,
        object ...$entities
    ): void {
        $this->mock
            ->expects($this->once())
            ->method('matching')
            ->with($criteria)
            ->willReturnCallback(fn (): DoctrineTestCollection => new DoctrineTestCollection($entities));
    }
}
