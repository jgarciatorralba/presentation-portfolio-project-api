<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Infrastructure\Persistence\Doctrine;

use App\Projects\Domain\Project;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Projects\Infrastructure\Persistence\Doctrine\DoctrineProjectRepository;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\Tests\Support\Builder\Projects\Domain\MappedProjectsBuilder;
use App\Tests\Support\Builder\Projects\Domain\ProjectBuilder;
use App\Tests\Support\Builder\Projects\Domain\ValueObject\ProjectIdBuilder;
use App\Tests\Support\Builder\Shared\Domain\Criteria\CriteriaBuilder;
use App\Tests\Unit\Shared\TestCase\EntityManagerMock;
use App\Tests\Unit\Shared\TestCase\EntityRepositoryMock;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

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
        $this->project = ProjectBuilder::any()->build();

        $reflection = new \ReflectionClass(DoctrineRepository::class);
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
            ->shouldUpdateEntity();

        $result = $this->sut->delete($this->project);
        $this->assertNull($result);
    }

    #[DataProvider('dataReturnsProject')]
    public function testItReturnsProjectOrNull(ProjectId $id, ?Project $project): void
    {
        $this->entityRepositoryMock
            ->shouldFindEntity($id, $project);

        $result = $this->sut->find($id);
        $this->assertEquals($project, $result);
    }

    /**
     * @return array<string, array<ProjectId, Project|null>>
     */
    public static function dataReturnsProject(): array
    {
        $project = ProjectBuilder::any()->build();
        $projectId = ProjectIdBuilder::any()->build();

        return [
            'existing id' => [$project->id(), $project],
            'non-existent id' => [$projectId, null],
        ];
    }

    public function testItFindsProjectsMatchingCriteria(): void
    {
        $criteria = CriteriaBuilder::any()->build();
        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);
        $projects = MappedProjectsBuilder::any()->build()->all();

        $this->entityRepositoryMock
            ->shouldFindEntitiesMatchingCriteria($doctrineCriteria, ...$projects);

        $result = $this->sut->matching($criteria);
        $this->assertEquals($projects, $result);
    }

    public function testItCountsProjectsMatchingCriteria(): void
    {
        $criteria = CriteriaBuilder::any()->build();
        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);
        $projects = MappedProjectsBuilder::any()->build()->all();

        $this->entityRepositoryMock
            ->shouldFindEntitiesMatchingCriteria($doctrineCriteria, ...$projects);

        $result = $this->sut->countMatching($criteria);
        $this->assertEquals(count($projects), $result);
    }

    public function testItFindsAllProjects(): void
    {
        $projects = MappedProjectsBuilder::any()->build()->all();

        $this->entityRepositoryMock
            ->shouldFindAllEntities(...$projects);

        $result = $this->sut->findAll();
        $this->assertEquals($projects, $result);
    }
}
