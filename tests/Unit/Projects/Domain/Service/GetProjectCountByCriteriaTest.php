<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Service;

use App\Projects\Domain\Service\GetProjectCountByCriteria;
use App\Tests\Builder\Projects\Domain\MappedProjectsBuilder;
use App\Tests\Builder\Shared\Domain\Criteria\CriteriaBuilder;
use App\Tests\Unit\Projects\TestCase\ProjectRepositoryMock;
use PHPUnit\Framework\TestCase;

final class GetProjectCountByCriteriaTest extends TestCase
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

	public function testItReturnsProjectCountMatchingCriteria(): void
	{
		$mappedProjects = MappedProjectsBuilder::any()->build();

        $criteria = CriteriaBuilder::any()->build();
		$expectedCount = count($mappedProjects->all());

		$this->projectRepositoryMock
			->shouldCountProjectsMatchingCriteria(
				$criteria,
				$expectedCount
			);

		$sut = new GetProjectCountByCriteria(
			projectRepository: $this->projectRepositoryMock->getMock()
		);

		$result = $sut->__invoke($criteria);

		$this->assertEquals($expectedCount, $result);
	}
}
