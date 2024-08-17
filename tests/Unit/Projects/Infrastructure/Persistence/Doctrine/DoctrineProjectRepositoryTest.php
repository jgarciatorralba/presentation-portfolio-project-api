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

    public function testItCreatesProject(): void
    {
        $sut = new DoctrineProjectRepository(
            entityManager: $this->entityManagerMock->getMock()
        );
        $project = ProjectFactory::create();

        $this->entityManagerMock
            ->shouldPersistEntity($project);

        $result = $sut->create($project);
        $this->assertNull($result);
    }

    public function testItUpdatesProject(): void
    {
        $sut = new DoctrineProjectRepository(
            entityManager: $this->entityManagerMock->getMock()
        );
        $project = ProjectFactory::create();

        $this->entityManagerMock
            ->shouldUpdateEntity();

        $result = $sut->update($project);
        $this->assertNull($result);
    }

    public function testItDeletesProject(): void
    {
        $sut = new DoctrineProjectRepository(
            entityManager: $this->entityManagerMock->getMock()
        );
        $project = ProjectFactory::create();

        $this->entityManagerMock
            ->shouldRemoveEntity($project);

        $result = $sut->delete($project);
        $this->assertNull($result);
    }

    public function testItSoftDeletesProject(): void
    {
        $sut = new DoctrineProjectRepository(
            entityManager: $this->entityManagerMock->getMock()
        );
        $project = ProjectFactory::create();

        $this->entityManagerMock
            ->shouldUpdateEntity();

        $result = $sut->softDelete($project);
        $this->assertNull($result);
    }
}
