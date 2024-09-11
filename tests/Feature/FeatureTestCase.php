<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\HttpKernelBrowser;

abstract class FeatureTestCase extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;
    protected ?HttpKernelBrowser $client = null;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->entityManager = $this->getContainer()
            ->get(EntityManagerInterface::class);
    }

    /**
     * @template T of object
     * @param class-string<T> $className
     */
    protected function find(string $className, mixed $id): ?object
    {
        return $this->entityManager->find($className, $id);
    }

    protected function persist(object ...$entities): void
    {
        foreach ($entities as $entity) {
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
    }

    protected function remove(object $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    /**
     * @template T of EntityRepository
     * @param class-string<T> $className
     * @return T
     */
    protected function repository(string $className): EntityRepository
    {
        return $this->entityManager->getRepository($className);
    }

    /**
     * @return string[]
     */
    private function tables(): array
    {
        $notMappedSuperClassNames = array_filter(
            $this->entityManager
                ->getConfiguration()
                ->getMetadataDriverImpl()
                ->getAllClassNames(),
            fn (string $class): bool => false ===
                $this->entityManager
                    ->getClassMetadata($class)
                    ->isMappedSuperclass
        );

        return array_map(
            fn (string $class): string =>
                $this->entityManager
                    ->getClassMetadata($class)
                    ->getTableName(),
            $notMappedSuperClassNames
        );
    }

    protected function clearDatabase(): void
    {
        $connection = $this->entityManager->getConnection();
        foreach ($this->tables() as $table) {
            $connection->executeQuery(
                sprintf('TRUNCATE "%s" CASCADE;', $table)
            );
        }

        $this->entityManager->clear();
    }
}
