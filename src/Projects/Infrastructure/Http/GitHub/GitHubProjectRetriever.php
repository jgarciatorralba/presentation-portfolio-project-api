<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Http\GitHub;

use App\Projects\Domain\Contract\ExternalProjectRetriever;
use App\Projects\Domain\MappedProjects;
use App\Projects\Domain\Project;
use App\Projects\Domain\ValueObject\ProjectDetails;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Projects\Infrastructure\Http\BaseProjectRetriever;
use App\Shared\Domain\Exception\InvalidCodeRepositoryUrlException;
use App\Shared\Domain\Http\HttpHeader;
use App\Shared\Domain\Http\HttpHeaders;
use App\Shared\Domain\Http\QueryParam;
use App\Shared\Domain\Http\QueryParams;
use App\Shared\Domain\ValueObject\GitHubCodeRepository;
use App\Shared\Domain\ValueObject\Url;

final class GitHubProjectRetriever extends BaseProjectRetriever implements ExternalProjectRetriever
{
    private static int $page = 1;
    private const int DEFAULT_RESULTS_PER_PAGE = 30;

    /**
     * @throws \RuntimeException
     * @throws InvalidCodeRepositoryUrlException
     * @throws \InvalidArgumentException
     * @throws \DateMalformedStringException
     * @throws \DateInvalidTimeZoneException
     */
    #[\Override]
    public function retrieve(): MappedProjects
    {
        $projectData = [];

        do {
            $this->logger->info('Retrieving projects from GitHub');

            $headers = new HttpHeaders(
                new HttpHeader('Authorization', "Bearer {$this->apiToken}"),
                new HttpHeader('Accept', 'application/vnd.github+json'),
            );

            $queryParams = new QueryParams(
                new QueryParam('per_page', (string) self::DEFAULT_RESULTS_PER_PAGE),
                new QueryParam('page', (string) self::$page++),
            );

            $response = $this->httpClient->fetch('/user/repos', [
                'baseUri' => $this->baseUri->value(),
                'headers' => $headers,
                'query' => $queryParams
            ]);

            $content = $response->getBody()->getContents();
            $decodedResponse = json_decode($content, true);

            if (!empty($decodedResponse['error'])) {
                $this->logger->error('Error retrieving projects from GitHub', [
                    'statusCode' => $response->getStatusCode(),
                    'error' => $decodedResponse['error'],
                ]);
            }

            if (!empty($decodedResponse['content'])) {
                $projectData = array_merge($projectData, $decodedResponse['content']);
            }
        } while (
            $response->hasHeader('link')
                && str_contains($response->getHeaderLine('link'), 'rel="next"')
        );

        return new MappedProjects(
            ...array_map(
                $this->recreateProjectFromData(...),
                $projectData
            )
        );
    }

    /**
     * @param array{
     *      id: int,
     *      name: string,
     *      description: string|null,
     *      topics: list<string>|null,
     *      html_url: string,
     *      homepage: string|null,
     *      archived: bool,
     *      pushed_at: string
     * } $projectData
     *
     * @throws InvalidCodeRepositoryUrlException
     * @throws \InvalidArgumentException
     * @throws \DateMalformedStringException
     * @throws \DateInvalidTimeZoneException
     */
    protected function recreateProjectFromData(array $projectData): Project
    {
        $details = ProjectDetails::create(
            name: $projectData['name'],
            description: empty($projectData['description'])
                ? null
                : $projectData['description'],
            topics: empty($projectData['topics'])
                ? null
                : $projectData['topics'],
        );

        $repository = GitHubCodeRepository::fromUrlValue($projectData['html_url']);
        $homepage = empty($projectData['homepage'])
            ? null
            : Url::fromString($projectData['homepage']);

        return Project::create(
            id: ProjectId::create($projectData['id']),
            details: $details,
            repository: $repository,
            homepage: $homepage,
            archived: $projectData['archived'],
            lastPushedAt: new \DateTimeImmutable($projectData['pushed_at'], new \DateTimeZone('UTC')),
        );
    }
}
