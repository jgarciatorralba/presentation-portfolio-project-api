<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Application\Query\GetProjects;

use App\Projects\Application\Query\GetProjects\GetProjectsQueryHandler;
use App\Projects\Application\Query\GetProjects\GetProjectsResponse;
use App\Projects\Domain\Project;
use App\Tests\Unit\Projects\Application\Query\GetProjects\Factory\GetProjectsQueryFactory;
use App\Tests\Unit\Projects\Domain\Factory\ProjectFactory;
use App\Tests\Unit\Projects\TestCase\GetProjectsByCriteriaMock;
use App\Tests\Unit\Shared\Domain\Criteria\Factory\UpdatedBeforeDateTimeCriteriaFactory;
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

    public function testGetProjects(): void
    {
        $projects = ProjectFactory::createMany(10);

        $this->getProjectsByCriteria->shouldGetProjects(
            UpdatedBeforeDateTimeCriteriaFactory::create(),
            ...$projects
        );

        $result = $this->sut->__invoke(
            query: GetProjectsQueryFactory::create()
        );

        $this->assertInstanceof(GetProjectsResponse::class, $result);
        $this->assertEquals(
            array_map(
                fn (Project $project) => $project->toArray(),
                $projects
            ),
            $result->data()['projects']
        );
        $this->assertEquals(10, $result->data()['count']);
    }
}
