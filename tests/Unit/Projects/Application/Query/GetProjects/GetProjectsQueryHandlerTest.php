<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Application\Query\GetProjects;

use App\Projects\Application\Query\GetProjects\GetProjectsQueryHandler;
use App\Projects\Application\Query\GetProjects\GetProjectsResponse;
use App\Projects\Domain\Project;
use App\Tests\Builder\Projects\Application\Query\GetProjects\GetProjectsQueryBuilder;
use App\Tests\Builder\Projects\Domain\ProjectBuilder;
use App\Tests\Builder\Shared\Domain\Criteria\UpdatedBeforeDateTimeCriteriaBuilder;
use App\Tests\Unit\Projects\TestCase\GetProjectsByCriteriaMock;
use PHPUnit\Framework\TestCase;

final class GetProjectsQueryHandlerTest extends TestCase
{
    private ?GetProjectsByCriteriaMock $getProjectsByCriteria;
    private ?GetProjectsQueryHandler $sut;

    protected function setUp(): void
    {
        $this->getProjectsByCriteria = new GetProjectsByCriteriaMock($this);
        $this->sut = new GetProjectsQueryHandler(
            getProjectsByCriteria: $this->getProjectsByCriteria->getMock()
        );
    }

    protected function tearDown(): void
    {
        $this->getProjectsByCriteria = null;
        $this->sut = null;
    }

    public function testItGetsProjects(): void
    {
        $projects = ProjectBuilder::buildMany(10);

        $this->getProjectsByCriteria->shouldGetProjects(
            UpdatedBeforeDateTimeCriteriaBuilder::any()->build(),
            ...$projects
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
        $this->assertEquals(10, $result->data()['count']);
    }
}
