<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Application\EventSubscriber;

use App\Projects\Application\EventSubscriber\ProjectModifiedSubscriber;
use App\Projects\Domain\Bus\Event\ProjectModifiedEvent;
use App\Projects\Domain\Exception\ProjectNotFoundException;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Tests\Support\Builder\Projects\Domain\ProjectBuilder;
use App\Tests\Unit\Projects\TestCase\UpdateProjectMock;
use App\Tests\Unit\Shared\TestCase\LoggerMock;
use PHPUnit\Framework\TestCase;

final class ProjectModifiedSubscriberTest extends TestCase
{
    private ?ProjectModifiedEvent $event;
    private ?UpdateProjectMock $updateProject;
    private ?LoggerMock $logger;
    private ?ProjectModifiedSubscriber $sut;

    protected function setUp(): void
    {
        $project = ProjectBuilder::any()->build();

        $this->event = new ProjectModifiedEvent(
            $project->id()
        );

        $this->updateProject = new UpdateProjectMock($this);
        $this->logger = new LoggerMock($this);
        $this->sut = new ProjectModifiedSubscriber(
            updateProject: $this->updateProject->getMock(),
            logger: $this->logger->getMock()
        );
    }

    protected function tearDown(): void
    {
        $this->event = null;
        $this->updateProject = null;
        $this->logger = null;
        $this->sut = null;
    }

    public function testItHandlesProjectModifiedEvent(): void
    {
        $this->updateProject->shouldUpdateProject(
            ProjectId::create((int) $this->event->aggregateId())
        );
        $this->logger->shouldLogInfo(
            'ProjectModifiedEvent handled.',
            [
                'projectId' => $this->event->aggregateId(),
            ]
        );

        $this->sut->__invoke($this->event);
    }

    public function testItLogsErrorWhenProjectModificationFails(): void
    {
        $this->updateProject->shouldThrowException(
            ProjectId::create((int) $this->event->aggregateId())
        );
        $this->logger->shouldLogError(
            'ProjectModifiedEvent failed.',
            [
                'projectId' => $this->event->aggregateId(),
                'error' => sprintf(
                    "Project with id '%s' could not be found.",
                    $this->event->aggregateId()
                ),
            ]
        );

        $this->expectException(ProjectNotFoundException::class);
        $this->sut->__invoke($this->event);
    }
}
