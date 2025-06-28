<?php

declare(strict_types=1);

namespace App\Tests\Integration\Projects\Infrastructure\Persistence\Doctrine;

use App\Projects\Domain\Project;
use App\Projects\Infrastructure\Persistence\Doctrine\DoctrineProjectRepository;
use App\Shared\Domain\Criteria\PushedBeforeDateTimeCriteria;
use App\Tests\Builder\Projects\Domain\ProjectBuilder;
use App\Tests\Builder\Projects\Domain\ValueObject\ProjectDetailsBuilder;
use App\Tests\Integration\IntegrationTestCase;

final class DoctrineProjectRepositoryTest extends IntegrationTestCase
{
    private const int MIN_PROJECTS = 1;
    private const int MAX_PROJECTS = 50;

    private DoctrineProjectRepository $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = $this->getContainer()
            ->get(DoctrineProjectRepository::class);
    }

    protected function tearDown(): void
    {
        $this->clearDatabase();

        parent::tearDown();
    }

    public function testItCreatesAProject(): void
    {
        $project = ProjectBuilder::any()->build();

        $this->sut->create($project);

        $fetchedProject = $this->findOneBy(Project::class, ['id' => $project->id()->value()]);

        $this->assertEquals($project, $fetchedProject);
    }

    public function testItUpdatesAProject(): void
    {
        $project = ProjectBuilder::any()
            ->withName('Initial Project Name')
            ->build();

        $this->persist($project);

        $updatedDetails = ProjectDetailsBuilder::any()
            ->withName('Updated Project Name')
            ->withDescription($project->details()->description())
            ->withTopics($project->details()->topics())
            ->build();

        $updatedProject = Project::recreate(
            id: $project->id(),
            details: $updatedDetails,
            urls: $project->urls(),
            archived: $project->archived(),
            lastPushedAt: $project->lastPushedAt()
        );

        $project->synchronizeWith($updatedProject);
        $this->sut->update($project);

        $fetchedProject = $this->findOneBy(Project::class, ['id' => $project->id()->value()]);

        $this->assertSame($updatedDetails->name(), $fetchedProject->details()->name());
        $this->assertTrue($fetchedProject->equals($project));
    }

    public function testItDeletesAProject(): void
    {
        $project = ProjectBuilder::any()
            ->withName('Initial Project Name')
            ->build();

        $this->persist($project);

        $this->sut->delete($project);

        $fetchedProject = $this->findOneBy(Project::class, ['id' => $project->id()->value()]);

        $this->assertNull($fetchedProject);

        $connection = $this->connection();
        $sql = 'SELECT * FROM projects WHERE id = :id';
        $result = $connection->fetchAssociative($sql, ['id' => $project->id()->value()]);

        $this->assertNotNull($result['deleted_at']);
    }

    public function testItFindsAProject(): void
    {
        $project = ProjectBuilder::any()
            ->build();

        $this->persist($project);

        $foundProject = $this->sut->find($project->id());

        $this->assertNotNull($foundProject);
        $this->assertEquals($project, $foundProject);
    }

    public function testItFindsAllProjects(): void
    {
        $randomCount = random_int(self::MIN_PROJECTS, self::MAX_PROJECTS);
        for ($i = 0; $i < $randomCount; $i++) {
            $project = ProjectBuilder::any()->build();
            $this->persist($project);
        }

        $foundProjectsCount = $this->sut->findAll();

        $this->assertCount($randomCount, $foundProjectsCount);
    }

    public function testItFindsProjectsMatchingCriteria(): void
    {
        $maxLastPushedAt = new \DateTimeImmutable('now -1day');
        $criteria = new PushedBeforeDateTimeCriteria($maxLastPushedAt);

        $projectMatchingCriteria = ProjectBuilder::any()
                ->withLastPushedAt(new \DateTimeImmutable('now -2day'))
                ->build();

        $randomCount = random_int(self::MIN_PROJECTS, self::MAX_PROJECTS);
        $projects = [];
        for ($i = 0; $i < $randomCount; $i++) {
            $projects[] = ProjectBuilder::any()
                ->withLastPushedAt(new \DateTimeImmutable('now'))
                ->build();
        }

        $this->persist($projectMatchingCriteria, ...$projects);

        $foundProjects = $this->sut->matching($criteria);

        $this->assertEquals($projectMatchingCriteria, $foundProjects[0]);
    }

    public function testItCountsProjectsMatchingCriteria(): void
    {
        $maxLastPushedAt = new \DateTimeImmutable('now -1day');
        $criteria = new PushedBeforeDateTimeCriteria($maxLastPushedAt);

        $randomCount = random_int(self::MIN_PROJECTS, self::MAX_PROJECTS);
        $randomCountMatchingCriteria = random_int(0, $randomCount);

        for ($i = 0; $i < $randomCount; $i++) {
            $project = ProjectBuilder::any();

            $project->withLastPushedAt(
                $i < $randomCountMatchingCriteria
                    ? new \DateTimeImmutable('now -2days')
                    : new \DateTimeImmutable('now')
            );

            $this->persist($project->build());
        }

        $foundProjectsCount = $this->sut->countMatching($criteria);

        $this->assertEquals($randomCountMatchingCriteria, $foundProjectsCount);
    }
}
