<?php

declare(strict_types=1);

namespace App\Tests\Feature\Projects;

use App\Projects\Domain\Project;
use App\Shared\Utils;
use App\Tests\Feature\FeatureTestCase;
use App\Tests\Builder\Projects\Domain\ProjectBuilder;
use App\UI\Exception\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;

final class CreateProjectFeatureTest extends FeatureTestCase
{
    public function testItCreatesProject(): void
    {
        $project = ProjectBuilder::any()->build();

        $content = [
            'id' => $project->id()->value(),
            'name' => $project->details()->name(),
            'repository' => $project->urls()->repository()->value(),
            'archived' => $project->archived(),
            'lastPushedAt' => Utils::dateToString($project->lastPushedAt()),
        ];

        if (null !== $project->details()->description()) {
            $content['description'] = $project->details()->description();
        }
        if (null !== $project->details()->topics()) {
            $content['topics'] = $project->details()->topics();
        }
        if (null !== $project->urls()->homepage()) {
            $content['homepage'] = $project->urls()->homepage()->value();
        }

        $this->client->request(
            method: 'POST',
            uri: 'api/projects',
            content: json_encode($content)
        );

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertIsString($response->getContent());

        $decodedResponse = json_decode($response->getContent(), true);
        $this->assertEmpty($decodedResponse);

        $project = $this->find(Project::class, $project->id());
        if ($project) {
            $this->remove($project);
        }
    }

    #[DataProvider('dataValidation')]
    /**
     * @param array<string, mixed> $content
     */
    public function testItThrowsValidationException(array $content): void
    {
        $this->client->catchExceptions(false);
        $this->expectException(ValidationException::class);

        $this->client->request(
            method: 'POST',
            uri: 'api/projects',
            content: json_encode($content)
        );
    }

    #[DataProvider('dataValidation')]
    /**
     * @param array<string, mixed> $content
     */
    public function testItReturnsValidationError(array $content): void
    {
        $this->client->request(
            method: 'POST',
            uri: 'api/projects',
            content: json_encode($content)
        );

        $response = $this->client->getResponse();
        $this->assertEquals($response->getStatusCode(), Response::HTTP_BAD_REQUEST);

        $decodedResponse = json_decode($response->getContent(), true);
        $this->assertIsArray($decodedResponse);
        $this->assertArrayHasKey('code', $decodedResponse);
        $this->assertEquals('validation_exception', $decodedResponse['code']);
        $this->assertArrayHasKey('errorMessage', $decodedResponse);
        $this->assertArrayHasKey('errors', $decodedResponse);
    }

    public static function dataValidation(): array
    {
        $projectData = ProjectBuilder::any()
            ->build()
            ->toArray();

        $missingIdData = $projectData;
        unset($missingIdData['id']);

        $blankNameData = $projectData;
        $blankNameData['name'] = '';

        $notGitHubRepositoryData = $projectData;
        $notGitHubRepositoryData['repository'] = str_replace(
            'github',
            'gitlab',
            $notGitHubRepositoryData['repository']
        );

        $nullArchivedData = $projectData;
        $nullArchivedData['archived'] = null;

        $invalidHomepageUrlData = $projectData;
        $invalidHomepageUrlData['homepage'] = 'invalid-url';

        $futureLastPushedAtData = $projectData;
        $futureLastPushedAtData['lastPushedAt'] = (new \DateTimeImmutable('2999-01-01'))
            ->format(\DateTimeInterface::ATOM);

        return [
            'missing id' => [$missingIdData],
            'blank name' => [$blankNameData],
            'not a github repository' => [$notGitHubRepositoryData],
            'null archived value' => [$nullArchivedData],
            'invalid homepage url' => [$invalidHomepageUrlData],
            'future lastPushedAt value' => [$futureLastPushedAtData],
        ];
    }
}
