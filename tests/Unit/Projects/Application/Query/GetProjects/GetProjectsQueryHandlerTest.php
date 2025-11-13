<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\Application\Query\GetProjects;

use App\Projects\Application\Query\GetProjects\GetProjectsQueryHandler;
use App\Projects\Application\Query\GetProjects\GetProjectsResponse;
use App\Projects\Domain\Project;
use Tests\Support\Builder\Projects\Application\Query\GetProjects\GetProjectsQueryBuilder;
use Tests\Support\Builder\Projects\Domain\MappedProjectsBuilder;
use Tests\Support\Builder\Shared\Domain\Criteria\PushedBeforeDateTimeCriteriaBuilder;
use Tests\Unit\Projects\TestCase\GetProjectCountByCriteriaMock;
use Tests\Unit\Projects\TestCase\GetProjectsByCriteriaMock;
use Tests\Unit\Shared\Domain\Testing\FakeValueGenerator;
use PHPUnit\Framework\TestCase;

final class GetProjectsQueryHandlerTest extends TestCase
{
    private ?GetProjectsByCriteriaMock $getProjectsByCriteria;
    private ?GetProjectCountByCriteriaMock $getProjectCountByCriteria;
    private ?GetProjectsQueryHandler $sut;

    protected function setUp(): void
    {
        $this->getProjectsByCriteria = new GetProjectsByCriteriaMock($this);
        $this->getProjectCountByCriteria = new GetProjectCountByCriteriaMock($this);
        $this->sut = new GetProjectsQueryHandler(
            getProjectsByCriteria: $this->getProjectsByCriteria->getMock(),
            getProjectCountByCriteria: $this->getProjectCountByCriteria->getMock()
        );
    }

    protected function tearDown(): void
    {
        $this->getProjectsByCriteria = null;
        $this->getProjectCountByCriteria = null;
        $this->sut = null;
    }

    public function testItGetsProjects(): void
    {
        $projects = iterator_to_array(MappedProjectsBuilder::any()->build()->getIterator(), false);
        $totalProjects = FakeValueGenerator::integer(min: count($projects));

        $this->getProjectsByCriteria->shouldGetProjects(
            PushedBeforeDateTimeCriteriaBuilder::any()->build(),
            ...$projects
        );

        $this->getProjectCountByCriteria->shouldGetProjectCount(
            PushedBeforeDateTimeCriteriaBuilder::any()->build(),
            $totalProjects
        );

        $result = $this->sut->__invoke(
            query: GetProjectsQueryBuilder::any()->build()
        );

        $this->assertInstanceof(GetProjectsResponse::class, $result);
        $this->assertEquals(
            array_map(
                fn (Project $project): array => $project->toArray(),
                $projects
            ),
            $result->data()['projects']
        );
        $this->assertEquals(count($projects), $result->count());
        $this->assertEquals($totalProjects, $result->totalCount());
    }
}
