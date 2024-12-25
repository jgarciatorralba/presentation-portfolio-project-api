<?php

declare(strict_types=1);

namespace App\Tests\Feature\Projects;

use App\Projects\Domain\Project;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Domain\Http\HttpStatusCode;
use App\Tests\Builder\Projects\Domain\ProjectBuilder;
use App\Tests\Builder\Projects\Domain\ValueObject\ProjectIdBuilder;
use App\Tests\Feature\FeatureTestCase;
use App\UI\Command\Projects\SyncProjectsCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class RequestProjectsSyncFeatureTest extends FeatureTestCase
{
    private const string BASE_URI = 'https://api.github.com';
    private const string FILE_PATH = '/Simulations/GitHub/user-projects.json';

    /** @var list<array<string, mixed>> $projectData */
    private ?array $projectData;
    private ?CommandTester $commandTester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->projectData = json_decode(
            file_get_contents(dirname(__DIR__, 2) . self::FILE_PATH),
            true
        );

        $mockResponse = new MockResponse(
            file_get_contents(dirname(__DIR__, 2) . self::FILE_PATH),
            [
                'http_code' => HttpStatusCode::HTTP_OK->value,
                'response_headers' => ['Content-Type' => 'application/json']
            ]
        );

        $this->getContainer()->set(
            id: HttpClientInterface::class,
            service: new MockHttpClient($mockResponse, self::BASE_URI)
        );

        $eventBus = $this->getContainer()->get(EventBus::class);
        $command = new SyncProjectsCommand($eventBus);
        $this->commandTester = new CommandTester($command);
    }

    protected function tearDown(): void
    {
        $this->projectData = null;
        $this->commandTester = null;
        $this->clearDatabase();
        $this->clearLogs();

        parent::tearDown();
    }

    public function testItSyncsByAddingNewProjects(): void
    {
        $this->commandTester->execute(input: []);

        $this->assertLogContains(
            message: '"message":"Retrieving projects from GitHub"'
        );

        foreach ($this->projectData as $projectDatum) {
            $this->assertLogContains(
                message: sprintf(
                    '"message":"ProjectAddedEvent handled.","context":{"projectId":"%s"}',
                    $projectDatum['id']
                )
            );

            $project = $this->findOneBy(
                className: Project::class,
                criteria: ['id' => $projectDatum['id']]
            );

            $this->assertNotNull($project);
            $this->assertInstanceOf(Project::class, $project);
            $this->assertProjectContainsProjectData($project, $projectDatum);
        }
    }

    public function testItSyncsByDeletingOldProjects(): void
    {
        $projects = ProjectBuilder::buildMany(10);

        $this->persist(...$projects);

        $this->commandTester->execute(input: []);

        $this->assertLogContains(
            message: '"message":"Retrieving projects from GitHub"'
        );

        foreach ($projects as $project) {
            $this->assertLogContains(
                message: sprintf(
                    '"message":"ProjectRemovedEvent handled.","context":{"projectId":"%s"}',
                    $project->id()->value()
                )
            );

            $projectFound = $this->findOneBy(
                className: Project::class,
                criteria: ['id' => $project->id()->value()]
            );

            $this->assertNull($projectFound);
        }
    }

    public function testItSyncsByUpdatingExistingProjects(): void
    {
        $projects = array_map(
            function (array $projectData): Project {
                $projectId = ProjectIdBuilder::any()
                    ->withValue($projectData['id'])
                    ->build();

                return ProjectBuilder::any()
                    ->withId($projectId)
                    ->build();
            },
            $this->projectData
        );

        $this->persist(...$projects);

        $this->commandTester->execute(input: []);

        $this->assertLogContains(
            message: '"message":"Retrieving projects from GitHub"'
        );

        foreach ($projects as $key => $project) {
            $this->assertLogContains(
                message: sprintf(
                    '"message":"ProjectModifiedEvent handled.","context":{"projectId":"%s"}',
                    $project->id()->value()
                )
            );

            $foundProject = $this->findOneBy(
                className: Project::class,
                criteria: ['id' => $project->id()->value()]
            );

            $this->assertNotNull($foundProject);
            $this->assertInstanceOf(Project::class, $foundProject);
            $this->assertProjectContainsProjectData($foundProject, $this->projectData[$key]);
        }
    }

    /** @param array<string, mixed> $projectData */
    private function assertProjectContainsProjectData(
        Project $project,
        array $projectData
    ): void {
        $this->assertEquals($projectData['id'], $project->id()->value());
        $this->assertEquals($projectData['name'], $project->details()->name());
        $this->assertEquals($projectData['description'], $project->details()->description());
        $this->assertEquals($projectData['topics'], $project->details()->topics());
        $this->assertEquals($projectData['html_url'], $project->urls()->repository());
        $this->assertEquals($projectData['homepage'], $project->urls()->homepage());
        $this->assertEquals($projectData['archived'], $project->archived());
        $this->assertEquals(
            \DateTimeImmutable::createFromFormat(
                'Y-m-d\TH:i:s\Z',
                $projectData['pushed_at'],
                new \DateTimeZone('UTC')
            ),
            $project->lastPushedAt()
        );
    }
}
