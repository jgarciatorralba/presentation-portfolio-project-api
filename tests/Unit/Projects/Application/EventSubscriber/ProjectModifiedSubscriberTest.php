<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Application\EventSubscriber;

use App\Projects\Application\EventSubscriber\ProjectModifiedSubscriber;
use App\Projects\Domain\Bus\Event\ProjectModifiedEvent;
use App\Projects\Domain\Exception\ProjectNotFoundException;
use App\Tests\Builder\Projects\Domain\ProjectBuilder;
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
        $this->event = new ProjectModifiedEvent(
            ProjectBuilder::any()->build()
        );

        $this->updateProject = new UpdateProjectMock();
        $this->logger = new LoggerMock();
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
        $this->updateProject->shouldUpdateProject($this->event->project());
        $this->logger->shouldLogInfo(
            'ProjectModifiedEvent handled.',
            [
                'projectId' => $this->event->project()->id()->value(),
            ]
        );

        $this->sut->__invoke($this->event);
    }

    public function testItLogsErrorWhenProjectModificationFails(): void
    {
        $this->updateProject->shouldThrowException($this->event->project());
        $this->logger->shouldLogError(
            'ProjectModifiedEvent failed.',
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
