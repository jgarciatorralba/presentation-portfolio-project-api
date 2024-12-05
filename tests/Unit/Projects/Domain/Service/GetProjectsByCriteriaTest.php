<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Service;

use App\Projects\Domain\Service\GetProjectsByCriteria;
use App\Tests\Builder\Projects\Domain\ProjectBuilder;
use App\Tests\Builder\Shared\Domain\Criteria\CriteriaBuilder;
use App\Tests\Unit\Projects\TestCase\ProjectRepositoryMock;
use PHPUnit\Framework\TestCase;

class GetProjectsByCriteriaTest extends TestCase
{
    private ?ProjectRepositoryMock $projectRepositoryMock;

    protected function setUp(): void
    {
        $this->projectRepositoryMock = new ProjectRepositoryMock();
    }

    protected function tearDown(): void
    {
        $this->projectRepositoryMock = null;
    }

    public function testItReturnsProjectsMatchingCriteria(): void
    {
        $projects = ProjectBuilder::buildMany();
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
