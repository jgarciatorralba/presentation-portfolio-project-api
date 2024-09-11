<?php

declare(strict_types=1);

namespace App\Tests\Feature\UI\Controller\Projects;

use App\Projects\Domain\Project;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Utils;
use App\Tests\Feature\FeatureTestCase;
use App\Tests\Unit\Projects\Domain\Factory\ProjectFactory;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[DataProvider('dataParameters')]
    /**
     * @param array<string, int|string> $params
     */
    public function testItGetsProjects(array $params): void
    {
        $this->client->request(
            method: 'GET',
            uri: 'api/projects',
            parameters: $params
        );

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertIsString($response->getContent());

        $decodedResponse = json_decode($response->getContent(), true);
        $this->assertIsArray($decodedResponse);
        $this->assertArrayHasKey('count', $decodedResponse);
        $this->assertArrayHasKey('projects', $decodedResponse);

        $expectedCount = count($this->projects);
        if (isset($params['pageSize']) && $params['pageSize'] < $expectedCount) {
            $expectedCount = $params['pageSize'];
        } elseif (isset($params['maxCreatedAt'])) {
            $expectedCount = 0;
        }
        if ($this->getMaxPageSize() < $expectedCount) {
            $expectedCount = $this->getMaxPageSize();
        }

        $expectedProjects = array_map(
            fn (Project $project): array => $project->toArray(),
            array_slice($this->projects, 0, $expectedCount)
        );

        $this->assertEquals($expectedCount, $decodedResponse['count']);
        $this->assertEquals($expectedProjects, $decodedResponse['projects']);
    }

    private function getMaxPageSize(): int
    {
        $reflectionClass = new \ReflectionClass(Criteria::class);
        $constants = $reflectionClass->getConstants();
        return $constants['MAX_PAGE_SIZE'];
    }

    /**
     * @return array<string, array<array<string, int|string>>>
     */
    public static function dataParameters(): array
    {
        return [
            'no query parameters' => [[]],
            'defined pageSize and no maxCreatedAt' => [[
                'pageSize' => 10
            ]],
            'defined maxCreatedAt and no pageSize' => [[
                'maxCreatedAt' => Utils::dateToString(new \DateTimeImmutable('yesterday'))
            ]],
        ];
    }
}
