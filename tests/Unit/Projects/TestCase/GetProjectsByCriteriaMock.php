<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\TestCase;

use App\Projects\Domain\Project;
use App\Projects\Domain\Service\GetProjectsByCriteria;
use App\Shared\Domain\Criteria\Criteria;
use Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\InvalidArgumentException;

final class GetProjectsByCriteriaMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return GetProjectsByCriteria::class;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function shouldGetProjects(object $criteria, Project ...$projects): void
    {
        if (!$criteria instanceof Criteria) {
            throw new \InvalidArgumentException('Expected instance of Criteria or its subclasses');
        }

        $this->mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->anything())
            ->willReturn($projects);
    }
}
