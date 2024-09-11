<?php

declare(strict_types=1);

namespace App\Tests\Feature\UI\Controller\Projects;

use App\Projects\Domain\Project;
use App\Shared\Domain\Criteria\Criteria;
use App\Tests\Feature\FeatureTestCase;
use App\Tests\Unit\Projects\Domain\Factory\ProjectFactory;
use Symfony\Component\HttpFoundation\Response;

final class GetProjectsControllerTest extends FeatureTestCase
{
    /** @var Project[] */
    private ?array $projects;

    protected function setUp(): void
    {
        parent::setUp();

        $projects = ProjectFactory::createMany(55);
        if (!empty($projects)) {
            $this->persist(...$projects);
            sleep(1);
        }

        usort($projects, function (Project $a, Project $b) {
            return $a->createdAt()->getTimestamp() <=> $b->createdAt()->getTimestamp();
        });

        $this->projects = $projects;
    }

    protected function tearDown(): void
    {
        $this->projects = null;
        $this->clearDatabase();

        parent::tearDown();
    }

    public function testItGetsProjects(): void
    {
        $this->client->request(
            method: 'GET',
            uri: 'api/projects',
        );

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertIsString($response->getContent());

        $decodedResponse = json_decode($response->getContent(), true);
        $this->assertIsArray($decodedResponse);
        $this->assertArrayHasKey('count', $decodedResponse);
        $this->assertArrayHasKey('projects', $decodedResponse);

        $this->assertCount(
            count($this->projects) > $this->getMaxPageSize()
                ? $this->getMaxPageSize()
                : count($this->projects),
            $decodedResponse['projects']
        );

        $this->assertEquals(
            array_map(
                fn (Project $project): array => $project->toArray(),
                array_slice($this->projects, 0, $this->getMaxPageSize())
            ),
            $decodedResponse['projects']
        );
    }

    private function getMaxPageSize(): int
    {
        $reflectionClass = new \ReflectionClass(Criteria::class);
        $constants = $reflectionClass->getConstants();
        return $constants['MAX_PAGE_SIZE'];
    }
}
