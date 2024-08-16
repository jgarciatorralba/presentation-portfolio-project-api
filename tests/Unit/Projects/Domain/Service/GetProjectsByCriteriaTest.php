<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Service;

use App\Projects\Domain\Service\GetProjectsByCriteria;
use App\Tests\Unit\Projects\Domain\Factory\ProjectFactory;
use App\Tests\Unit\Projects\TestCase\ProjectRepositoryMock;
use App\Tests\Unit\Shared\Domain\Criteria\Factory\CriteriaFactory;
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
        $projects = ProjectFactory::createMany();
        $criteria = CriteriaFactory::create();

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
