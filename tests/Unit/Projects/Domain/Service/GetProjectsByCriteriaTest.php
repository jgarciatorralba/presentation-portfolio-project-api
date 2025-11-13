<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\Domain\Service;

use App\Projects\Domain\Service\GetProjectsByCriteria;
use Tests\Support\Builder\Projects\Domain\MappedProjectsBuilder;
use Tests\Support\Builder\Shared\Domain\Criteria\CriteriaBuilder;
use Tests\Unit\Projects\TestCase\ProjectRepositoryMock;
use PHPUnit\Framework\TestCase;

class GetProjectsByCriteriaTest extends TestCase
{
    private ?ProjectRepositoryMock $projectRepositoryMock;

    protected function setUp(): void
    {
        $this->projectRepositoryMock = new ProjectRepositoryMock($this);
    }

    protected function tearDown(): void
    {
        $this->projectRepositoryMock = null;
    }

    public function testItReturnsProjectsMatchingCriteria(): void
    {
        $projects = iterator_to_array(MappedProjectsBuilder::any()->build()->getIterator(), false);
        $criteria = CriteriaBuilder::any()->build();

        $this->projectRepositoryMock
            ->shouldFindProjectsMatchingCriteria(
                $criteria,
                ...$projects
            );

        $sut = new GetProjectsByCriteria(
            projectRepository: $this->projectRepositoryMock->getMock()
        );
        $result = $sut->__invoke($criteria);

        $this->assertEquals($projects, $result);
    }
}
