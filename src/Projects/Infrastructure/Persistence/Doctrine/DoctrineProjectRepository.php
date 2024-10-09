<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\Projects\Domain\Contract\ProjectRepository;
use App\Projects\Domain\Project;
use App\Projects\Domain\ValueObject\ProjectId;

class DoctrineProjectRepository extends DoctrineRepository implements ProjectRepository
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
        $this->updateEntity();
    }

    public function delete(Project $project): void
    {
        $project->updateDeletedAt(new \DateTimeImmutable());

        $this->updateEntity();
    }

    public function find(ProjectId $id): Project|null
    {
        return $this->repository()->find($id->value());
    }

    /** @return Project[] */
    public function findAll(): array
    {
        return $this->repository()->findAll();
    }

    /** @return Project[] */
    public function matching(Criteria $criteria): array
    {
        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);

        return $this->repository()
            ->matching($doctrineCriteria)
            ->toArray();
    }
}
