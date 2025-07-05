<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\Projects\Domain\Contract\ProjectRepository;
use App\Projects\Domain\Project;
use App\Projects\Domain\ValueObject\ProjectId;

/**
 * @extends DoctrineRepository<Project>
 */
readonly class DoctrineProjectRepository extends DoctrineRepository implements ProjectRepository
{
    protected function entityClass(): string
    {
        return Project::class;
    }

    public function create(Project $project): void
    {
        $this->persist($project);
    }

    public function update(Project $project): void
    {
        $project->updateUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));

        $this->updateEntity();
    }

    public function delete(Project $project): void
    {
        $project->updateDeletedAt(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));

        $this->updateEntity();
    }

    public function find(ProjectId $id): Project|null
    {
        return $this->repository()->findOneBy([
            'id' => $id->value()
        ]);
    }

    /** @return Project[] */
    public function findAll(): array
    {
        return $this->repository()->findAll();
    }

    /**
     * @return Project[]
     *
     * @throws \TypeError
     * @throws \ValueError
     * @throws \RuntimeException
     */
    public function matching(Criteria $criteria): array
    {
        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);

        return $this->repository()
            ->matching($doctrineCriteria)
            ->toArray();
    }

    /**
     * @throws \TypeError
     * @throws \ValueError
     * @throws \RuntimeException
     */
    public function countMatching(Criteria $criteria): int
    {
        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);

        return $this->repository()
            ->matching($doctrineCriteria)
            ->count();
    }
}
