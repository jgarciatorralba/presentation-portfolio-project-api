<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Application\EventSubscriber;

use App\Projects\Application\EventSubscriber\ProjectRemovedSubscriber;
use App\Projects\Domain\Bus\Event\ProjectRemovedEvent;
use App\Projects\Domain\Exception\ProjectNotFoundException;
use App\Tests\Builder\Projects\Domain\ProjectBuilder;
use App\Tests\Unit\Projects\TestCase\DeleteProjectMock;
use App\Tests\Unit\Shared\TestCase\LoggerMock;
use PHPUnit\Framework\TestCase;

final class ProjectRemovedSubscriberTest extends TestCase
{
    private ?ProjectRemovedEvent $event;
    private ?DeleteProjectMock $deleteProject;
    private ?LoggerMock $logger;
    private ?ProjectRemovedSubscriber $sut;

    protected function setUp(): void
    {
        $this->event = new ProjectRemovedEvent(
            ProjectBuilder::any()->build()
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
        $this->deleteProject->shouldDeleteProject($this->event->project());
        $this->logger->shouldLogInfo(
            'ProjectRemovedEvent handled.',
            [
                'projectId' => $this->event->project()->id()->value(),
            ]
        );

        $this->sut->__invoke($this->event);
    }

    public function testItLogsErrorWhenProjectDeletionFails(): void
    {
        $this->deleteProject->shouldThrowException($this->event->project());
        $this->logger->shouldLogError(
            'ProjectRemovedEvent failed.',
            [
                'projectId' => $this->event->project()->id()->value(),
                'error' => sprintf(
                    "Project with id '%s' could not be found.",
                    $this->event->project()->id()->value()
                ),
            ]
        );

        $this->expectException(ProjectNotFoundException::class);
        $this->sut->__invoke($this->event);
    }
}
