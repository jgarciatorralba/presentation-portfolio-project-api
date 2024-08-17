<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Infrastructure\Persistence\Doctrine;

use App\Projects\Domain\Project;
use App\Projects\Infrastructure\Persistence\Doctrine\DoctrineProjectRepository;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\Tests\Unit\Projects\Domain\Factory\ProjectFactory;
use App\Tests\Unit\Projects\TestCase\EntityManagerMock;
use App\Tests\Unit\Projects\TestCase\EntityRepositoryMock;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class DoctrineProjectRepositoryTest extends TestCase
{
    private ?EntityManagerMock $entityManagerMock;
    private ?EntityRepositoryMock $entityRepositoryMock;
    private ?DoctrineProjectRepository $sut;
    private ?Project $project;

    protected function setUp(): void
    {
        $this->entityManagerMock = new EntityManagerMock($this);
        $this->entityRepositoryMock = new EntityRepositoryMock($this);
        $this->sut = new DoctrineProjectRepository(
            entityManager: $this->entityManagerMock->getMock()
        );
        $this->project = ProjectFactory::create();

        $reflection = new ReflectionClass(DoctrineRepository::class);
        $property = $reflection->getProperty('repository');
        $property->setAccessible(true);
        $property->setValue($this->sut, $this->entityRepositoryMock->getMock());
    }

    protected function tearDown(): void
    {
        $this->entityManagerMock = null;
        $this->entityRepositoryMock = null;
        $this->sut = null;
        $this->project = null;
    }

    public function testItCreatesProject(): void
    {
        $this->entityManagerMock
            ->shouldPersistEntity($this->project);

        $result = $this->sut->create($this->project);
        $this->assertNull($result);
    }

    public function testItUpdatesProject(): void
    {
        $this->entityManagerMock
            ->shouldUpdateEntity();

        $result = $this->sut->update($this->project);
        $this->assertNull($result);
    }

    public function testItDeletesProject(): void
    {
        $this->entityManagerMock
            ->shouldRemoveEntity($this->project);

        $result = $this->sut->delete($this->project);
        $this->assertNull($result);
    }

    public function testItSoftDeletesProject(): void
    {
        $this->entityManagerMock
            ->shouldUpdateEntity();

        $result = $this->sut->softDelete($this->project);
        $this->assertNull($result);
    }

    #[DataProvider('dataFindsProject')]
    public function testItFindsProjectOrReturnsNull(int $id, ?Project $project): void
    {
        $this->entityRepositoryMock
            ->shouldFindEntity($id, $project);

        $result = $this->sut->find($id);
        $this->assertEquals($project, $result);
    }

    /**
     * @return array<string, array<int, Project|null>>
     */
    public static function dataFindsProject(): array
    {
        $project = ProjectFactory::create();

        return [
            'existing id' => [$project->id(), $project],
            'non-existent id' => [FakeValueGenerator::integer(), null],
        ];
    }
}
