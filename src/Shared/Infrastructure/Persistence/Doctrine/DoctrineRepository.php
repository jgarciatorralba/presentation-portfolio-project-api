<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Aggregate\AggregateRoot;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @template T of object
 */
abstract readonly class DoctrineRepository
{
    /** @var EntityRepository<T> $repository */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        /** @var class-string<T> $className */
        $className = $this->entityClass();
        $this->repository = $entityManager->getRepository($className);
    }

    protected function entityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /** @return EntityRepository<T> */
    protected function repository(): EntityRepository
    {
        return $this->repository;
    }

    protected function persist(AggregateRoot $entity): void
    {
        $this->entityManager()->persist($entity);
        $this->entityManager()->flush();
    }

    protected function updateEntity(): void
    {
        $this->entityManager()->flush();
    }

    abstract protected function entityClass(): string;
}
