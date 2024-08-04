<?php

declare(strict_types=1);

namespace App\Projects\Domain\Contract;

use App\Projects\Domain\Project;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\ValueObject\Uuid;

interface ProjectRepository
{
    public function create(Project $project): void;

    public function update(Project $project): void;

    public function delete(Project $project): void;

    public function softDelete(Project $project): void;

    public function find(Uuid $id): Project|null;

    /** @return Project[] */
    public function matching(Criteria $criteria): array;
}
