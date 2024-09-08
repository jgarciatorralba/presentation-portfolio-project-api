<?php

declare(strict_types=1);

namespace App\Tests\Feature\UI\Controller\Projects;

use App\Projects\Domain\Project;
use App\Shared\Utils;
use App\Tests\Feature\FeatureTestCase;
use App\Tests\Unit\Projects\Domain\Factory\ProjectFactory;
use Symfony\Component\HttpFoundation\Response;

final class CreateProjectControllerTest extends FeatureTestCase
{
    public function testItCreatesProject(): void
    {
        $project = ProjectFactory::create();

        $content = [
            'id' => $project->id(),
            'name' => $project->details()->name(),
            'repository' => $project->urls()->repository(),
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
            $content['homepage'] = $project->urls()->homepage();
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
}
