<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Infrastructure\Http\GitHub;

use App\Projects\Domain\Contract\ExternalProjectRetriever;
use App\Projects\Domain\Project;
use App\Projects\Infrastructure\Http\GitHub\GitHubProjectRetriever;
use App\Tests\Unit\Shared\TestCase\HttpClientMock;
use App\Tests\Unit\Shared\TestCase\LoggerMock;
use App\Tests\Unit\Shared\TestCase\LocalDateTimeZoneConverterMock;
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

	public function testItRetrievesProjects(): void
	{
		$externalRequests = 1;

		for ($i = 0; $i < $externalRequests; $i++) {
			$this->loggerMock
				->shouldLogInfo('Retrieving projects from GitHub');
		}

		$this->httpClientMock
			->shouldFetchSuccessfully(
				times: $externalRequests,
				content: json_decode(
					file_get_contents(dirname(__DIR__, 5) . self::FILE_PATH),
					true
				)
			);

		$projects = $this->sut->retrieve();

		$this->assertIsArray($projects);
		$this->assertCount(3, $projects);
		$this->assertInstanceof(Project::class, $projects[0]);
	}
}
