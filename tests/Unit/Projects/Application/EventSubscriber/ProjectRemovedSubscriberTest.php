<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\Application\EventSubscriber;

use App\Projects\Application\EventSubscriber\ProjectRemovedSubscriber;
use App\Projects\Domain\Bus\Event\ProjectRemovedEvent;
use App\Projects\Domain\Exception\ProjectNotFoundException;
use App\Projects\Domain\ValueObject\ProjectId;
use Tests\Support\Builder\Projects\Domain\ProjectBuilder;
use Tests\Unit\Projects\TestCase\DeleteProjectMock;
use Tests\Unit\Shared\TestCase\LoggerMock;
use PHPUnit\Framework\TestCase;

final class ProjectRemovedSubscriberTest extends TestCase
{
    private ?ProjectRemovedEvent $event;
    private ?DeleteProjectMock $deleteProject;
    private ?LoggerMock $logger;
    private ?ProjectRemovedSubscriber $sut;

    protected function setUp(): void
    {
        $project = ProjectBuilder::any()->build();

        $this->event = new ProjectRemovedEvent(
            $project->id()
        );

        $this->deleteProject = new DeleteProjectMock($this);
        $this->logger = new LoggerMock($this);
        $this->sut = new ProjectRemovedSubscriber(
            deleteProject: $this->deleteProject->getMock(),
            logger: $this->logger->getMock()
        );
    }

    protected function tearDown(): void
    {
        $this->event = null;
        $this->deleteProject = null;
        $this->logger = null;
        $this->sut = null;
    }

    public function testItHandlesProjectRemovedEvent(): void
    {
        $this->deleteProject->shouldDeleteProject(
            ProjectId::create((int) $this->event->aggregateId())
        );
        $this->logger->shouldLogInfo(
            'ProjectRemovedEvent handled.',
            [
                'projectId' => $this->event->aggregateId(),
            ]
        );

        $this->sut->__invoke($this->event);
    }

    public function testItLogsErrorWhenProjectDeletionFails(): void
    {
        $this->deleteProject->shouldThrowException(
            ProjectId::create((int) $this->event->aggregateId())
        );
        $this->logger->shouldLogError(
            'ProjectRemovedEvent failed.',
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
