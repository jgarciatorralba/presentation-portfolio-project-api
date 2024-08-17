<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Infrastructure\Persistence\Doctrine;

use App\Projects\Infrastructure\Persistence\Doctrine\DoctrineProjectRepository;
use App\Tests\Unit\Projects\Domain\Factory\ProjectFactory;
use App\Tests\Unit\Projects\TestCase\EntityManagerMock;
use PHPUnit\Framework\TestCase;

final class DoctrineProjectRepositoryTest extends TestCase
{
    private ?EntityManagerMock $entityManagerMock;

    protected function setUp(): void
    {
        $this->entityManagerMock = new EntityManagerMock($this);
    }

    protected function tearDown(): void
    {
        $this->entityManagerMock = null;
    }

    public function testCreateProject(): void
    {
        $repository = new DoctrineProjectRepository(
            entityManager: $this->entityManagerMock->getMock()
        );
        $project = ProjectFactory::create();

        $this->entityManagerMock
            ->shouldPersistEntity($project);

        $result = $repository->create($project);
        $this->assertNull($result);
    }
}
