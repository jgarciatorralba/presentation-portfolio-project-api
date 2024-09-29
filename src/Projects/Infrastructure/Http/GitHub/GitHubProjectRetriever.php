<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Http\GitHub;

use App\Projects\Domain\Contract\ExternalProjectRetriever;
use App\Projects\Domain\Project;
use App\Projects\Domain\ValueObject\ProjectDetails;
use App\Projects\Domain\ValueObject\ProjectUrls;
use App\Projects\Infrastructure\Http\BaseProjectRetriever;
use App\Shared\Domain\Contract\HttpClient;
use App\Shared\Domain\Contract\Logger;
use App\Shared\Domain\Service\LocalDateTimeZoneConverter;

final class GitHubProjectRetriever extends BaseProjectRetriever implements ExternalProjectRetriever
{
    private static int $page = 1;
    private const DEFAULT_RESULTS_PER_PAGE = 30;

    public function __construct(
        private readonly LocalDateTimeZoneConverter $dateTimeConverter,
        string $apiToken,
        string $baseUri,
        HttpClient $httpClient,
        Logger $logger,
    ) {
        parent::__construct($apiToken, $baseUri, $httpClient, $logger);
    }

    /** @return list<Project> */
    public function retrieve(): array
    {
        $projectData = [];

        do {
            $this->logger->info('Retrieving projects from GitHub');

            $response = $this->httpClient->fetch('/user/repos', [
                'base_uri' => $this->baseUri,
                'headers' => [
                    'Authorization' => "Bearer {$this->apiToken}",
                    'Accept' => 'application/vnd.github+json',
                ],
                'query' => [
                    'per_page' => self::DEFAULT_RESULTS_PER_PAGE,
                    'page' => self::$page++,
                ]
            ]);

            if ($response->error()) {
                $this->logger->error('Error retrieving projects from GitHub', [
                    'statusCode' => $response->statusCode(),
                    'error' => $response->error(),
                ]);
            }

            if ($response->content()) {
                $decodedResponse = json_decode($response->content(), true);
                $projectData = array_merge($projectData, $decodedResponse);
            }
        } while (
            !empty($response->headers()['link'])
                && str_contains($response->headers()['link'][0], 'rel="next"')
        );

        return array_map(
            [$this, 'createProjectFromData'],
            $projectData
        );
    }

    /**
     * @param array{
     * 		id: int,
     * 		name: string,
     * 		description: string|null,
     * 		topics: list<string>|null,
     * 		html_url: string,
     * 		homepage: string|null,
     * 		archived: bool,
     * 		pushed_at: string
     * } $projectData
     */
    protected function createProjectFromData(array $projectData): Project
    {
        $projectDetails = ProjectDetails::create(
            name: $projectData['name'],
            description: !empty($projectData['description'])
                ? $projectData['description']
                : null,
            topics: !empty($projectData['topics'])
                ? $projectData['topics']
                : null,
        );

        $projectUrls = ProjectUrls::create(
            repository: $projectData['html_url'],
            homepage: !empty($projectData['homepage'])
                ? $projectData['homepage']
                : null,
        );

        return Project::create(
            id: $projectData['id'],
            details: $projectDetails,
            urls: $projectUrls,
            archived: $projectData['archived'],
            lastPushedAt: $this->dateTimeConverter->convert(
                new \DateTimeImmutable($projectData['pushed_at'])
            ),
        );
    }
}
