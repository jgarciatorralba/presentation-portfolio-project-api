<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Infrastructure\Http\GitHub;

use App\Projects\Domain\Contract\ExternalProjectRetriever;
use App\Projects\Domain\Project;
use App\Projects\Infrastructure\Http\GitHub\GitHubProjectRetriever;
use App\Shared\Domain\Http\HttpHeader;
use App\Tests\Unit\Shared\TestCase\HttpClientMock;
use App\Tests\Unit\Shared\TestCase\LoggerMock;
use App\Tests\Unit\Shared\TestCase\LocalDateTimeZoneConverterMock;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class GitHubProjectRetrieverTest extends TestCase
{
    private const string API_TOKEN = 'valid-api-token';
    private const string BASE_URI = 'https://example.com';
    private const string FILE_PATH = '/Simulations/GitHub/user-projects.json';

    private ?HttpClientMock $httpClientMock;
    private ?LoggerMock $loggerMock;
    private ?LocalDateTimeZoneConverterMock $dateTimeConverterMock;
    private ?GitHubProjectRetriever $sut;

    protected function setUp(): void
    {
        $this->httpClientMock = new HttpClientMock();
        $this->loggerMock = new LoggerMock();
        $this->dateTimeConverterMock = new LocalDateTimeZoneConverterMock();

        $this->sut = new GitHubProjectRetriever(
            dateTimeConverter: $this->dateTimeConverterMock->getMock(),
            apiToken: self::API_TOKEN,
            baseUri: self::BASE_URI,
            httpClient: $this->httpClientMock->getMock(),
            logger: $this->loggerMock->getMock(),
        );
    }

    protected function tearDown(): void
    {
        $this->httpClientMock = null;
        $this->loggerMock = null;
        $this->dateTimeConverterMock = null;
        $this->sut = null;
    }

    public function testItImplementsExternalProjectRetriever(): void
    {
        self::assertInstanceOf(ExternalProjectRetriever::class, $this->sut);
    }

    #[DataProvider('dataProjectsRetrieved')]
    /**
     * @param list<array{
     *      content: array,
     *      headers: list<HttpHeader>,
     * }> $chunks
     */
    public function testItRetrievesProjects(
        array $chunks
    ): void {
        $this->loggerMock
            ->shouldLogInfo(
                message: 'Retrieving projects from GitHub',
                times: count($chunks)
            );

        $this->httpClientMock
            ->shouldFetchSuccessfully($chunks);

        $projects = $this->sut->retrieve();

        $this->assertIsArray($projects);
        foreach ($projects as $project) {
            $this->assertInstanceof(Project::class, $project);
        }
    }

    /**
     * return array<string, array<list<array{
     *      content: array,
     *      headers: list<HttpHeader>,
     * }>>>
     */
    public static function dataProjectsRetrieved(): array
    {
        $results = json_decode(
            file_get_contents(dirname(__DIR__, 5) . self::FILE_PATH),
            true
        );

        return [
            'one chunk' => [[
                [
                    'content' => $results,
                    'headers' => [
                        new HttpHeader('Content-Type', 'application/json')
                    ]
                ]
            ]],
            'multiple chunks' => [[
                [
                    'content' => array_slice($results, 0, 1),
                    'headers' => [
                        new HttpHeader('Content-Type', 'application/json'),
                        new HttpHeader('Link', '<https://api.github.com/user/repos?page=2>; rel="next", <https://api.github.com/user/repos?page=2>;')
                    ]
                ],
                [
                    'content' => array_slice($results, 1, 1),
                    'headers' => [
                        new HttpHeader('Content-Type', 'application/json'),
                        new HttpHeader('Link', '<https://api.github.com/user/repos?page=2>; rel="next", <https://api.github.com/user/repos?page=3>; rel="last"')
                    ]
                ],
                [
                    'content' => array_slice($results, 2, 1),
                    'headers' => [
                        new HttpHeader('Content-Type', 'application/json')
                    ]
                ],
            ]]
        ];
    }
}
