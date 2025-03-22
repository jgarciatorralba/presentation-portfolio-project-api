<?php

declare(strict_types=1);

namespace App\Projects\Domain\Contract;

use App\Projects\Domain\Project;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Shared\Domain\Criteria\Criteria;

interface ProjectRepository
{
    public function create(Project $project): void;

    public function update(Project $project): void;

    public function delete(Project $project): void;

    public function find(ProjectId $id): Project|null;

    /** @return Project[] */
    public function findAll(): array;

    /** @return Project[] */
    public function matching(Criteria $criteria): array;

    public function countMatching(Criteria $criteria): int;
}
