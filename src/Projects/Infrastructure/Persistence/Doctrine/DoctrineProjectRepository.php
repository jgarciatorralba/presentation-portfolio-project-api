<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\Projects\Domain\Contract\ProjectRepository;
use App\Projects\Domain\Project;

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
        $this->remove($project);
    }

    public function softDelete(Project $project): void
    {
        $lastUpdatedAt = $project->updatedAt();
        $project->updateDeletedAt($lastUpdatedAt);

        $this->updateEntity();
    }

    public function find(Uuid $id): Project|null
    {
        return $this->repository()->find($id->value());
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
