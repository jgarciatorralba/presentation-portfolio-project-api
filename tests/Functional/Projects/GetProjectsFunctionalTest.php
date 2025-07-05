<?php

declare(strict_types=1);

namespace App\Tests\Functional\Projects;

use App\Projects\Application\Query\GetProjects\GetProjectsQuery;
use App\Projects\Domain\Project;
use App\Shared\Domain\Http\HttpStatusCode;
use App\Shared\Utils;
use App\Tests\Support\Builder\Projects\Domain\MappedProjectsBuilder;
use App\Tests\Functional\FunctionalTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class GetProjectsFunctionalTest extends FunctionalTestCase
{
    /** @var Project[] */
    private ?array $projects;

    protected function setUp(): void
    {
        parent::setUp();

        $projects = MappedProjectsBuilder::any()->build()->all();
        if ($projects !== []) {
            $this->persist(...$projects);
        }

        usort(
            $projects,
            fn (Project $a, Project $b): int =>
                $b->lastPushedAt()->getTimestamp() <=> $a->lastPushedAt()->getTimestamp()
        );

        $this->projects = $projects;
    }

    protected function tearDown(): void
    {
        $this->projects = null;
        $this->clearDatabase();

        parent::tearDown();
    }

    /**
     * @param array<string, int|string> $params
     */
    #[DataProvider('dataParameters')]
    public function testItGetsProjects(array $params, ?int $maxExpectedCount): void
    {
        $this->client->request(
            method: 'GET',
            uri: 'api/projects',
            parameters: $params
        );

        $response = $this->client->getResponse();
        $this->assertEquals(HttpStatusCode::HTTP_OK->value, $response->getStatusCode());
        $this->assertIsString($response->getContent());

        $decodedResponse = json_decode($response->getContent(), true);
        $this->assertIsArray($decodedResponse);
        $this->assertArrayHasKey('count', $decodedResponse);
        $this->assertArrayHasKey('projects', $decodedResponse);

        $expectedCount = count($this->projects);
        if ($maxExpectedCount !== null && $expectedCount > $maxExpectedCount) {
            $expectedCount = $maxExpectedCount;
        }
        if ($expectedCount > $this->getMaxPageSize()) {
            $expectedCount = $this->getMaxPageSize();
        }

        $expectedProjects = array_map(
            fn (Project $project): array => $project->toArray(),
            array_slice($this->projects, 0, $expectedCount)
        );

        $this->assertEquals($expectedCount, $decodedResponse['count']);
        $this->assertEquals($expectedProjects, $decodedResponse['projects']);

        $nextHeaderValue = '';
        if (count($this->projects) > ($maxExpectedCount ?? $this->getMaxPageSize())) {
            $nextHeaderValue = $maxExpectedCount === 0 ? '' : '1';
        }

        $this->assertEquals($response->headers->get('Content-Type'), 'application/json');
        $this->assertEquals($response->headers->get('Next'), $nextHeaderValue);
    }

    private function getMaxPageSize(): int
    {
        $reflectionClass = new \ReflectionClass(GetProjectsQuery::class);
        $constants = $reflectionClass->getConstants();
        return $constants['MAX_PAGE_SIZE'];
    }

    /**
     * @return array<string, array{
     *   params: array<string, int|string>,
     *   maxExpectedCount: int|null
     * }>
     */
    public static function dataParameters(): array
    {
        return [
            'no query parameters' => [
                'params' => [],
                'maxExpectedCount' => null,
            ],
            'defined pageSize and no maxPushedAt' => [
                'params' => [
                    'pageSize' => 10
                ],
                'maxExpectedCount' => 10,
            ],
            'defined maxPushedAt and no pageSize' => [
                'params' => [
                    'maxPushedAt' => Utils::dateToUTCString(
                        \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '1980-01-01 00:00:00')
                    )
                ],
                'maxExpectedCount' => 0,
            ],
        ];
    }
}
