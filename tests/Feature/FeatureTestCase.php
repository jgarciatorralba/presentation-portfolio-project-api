<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use Doctrine\DBAL\Exception as DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpKernel\HttpKernelBrowser;

abstract class FeatureTestCase extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;
    protected ?HttpKernelBrowser $client = null;

    /**
     * @throws ServiceCircularReferenceException
     * @throws ServiceNotFoundException
     * @throws \TypeError
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->entityManager = $this->getContainer()
            ->get(EntityManagerInterface::class);
    }

    protected function tearDown(): void
    {
        $this->client = null;
        $this->entityManager = null;

        parent::tearDown();
    }

    /**
     * @template T of object
     * @param class-string<T> $className
     * @param array<string, mixed> $criteria
     */
    protected function findOneBy(string $className, array $criteria): ?object
    {
        return $this->repository($className)->findOneBy($criteria);
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
     * @template T of object
     * @param class-string<T> $className
     * @return EntityRepository<T>
     */
    protected function repository(string $className): EntityRepository
    {
        return $this->entityManager->getRepository($className);
    }

    /** @throws DBALException */
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

    /**
     * @throws DBALException
     *
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

        $primaryTableNames = array_map(
            fn (string $class): string =>
                $this->entityManager
                    ->getClassMetadata($class)
                    ->getTableName(),
            $notMappedSuperClassNames
        );

        $existingTables = $this->schemaManager()
            ->listTableNames();

        return array_filter(
            $primaryTableNames,
            fn (string $table): bool => in_array($table, $existingTables)
        );
    }

    /**
     * @throws DBALException
     *
     * @return AbstractSchemaManager<AbstractPlatform>
     */
    private function schemaManager(): AbstractSchemaManager
    {
        return $this->entityManager
            ->getConnection()
            ->createSchemaManager();
    }
}
