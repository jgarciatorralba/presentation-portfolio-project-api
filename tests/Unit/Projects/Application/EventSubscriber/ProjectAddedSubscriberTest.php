<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\Application\EventSubscriber;

use App\Projects\Application\EventSubscriber\ProjectAddedSubscriber;
use App\Projects\Domain\Bus\Event\ProjectAddedEvent;
use App\Projects\Domain\Exception\ProjectAlreadyExistsException;
use Tests\Support\Builder\Projects\Domain\ProjectBuilder;
use Tests\Unit\Projects\TestCase\CreateProjectMock;
use Tests\Unit\Shared\TestCase\LoggerMock;
use PHPUnit\Framework\TestCase;

final class ProjectAddedSubscriberTest extends TestCase
{
    private ?ProjectAddedEvent $event;
    private ?CreateProjectMock $createProject;
    private ?LoggerMock $logger;
    private ?ProjectAddedSubscriber $sut;

    protected function setUp(): void
    {
        $this->event = new ProjectAddedEvent(
            ProjectBuilder::any()->build()
        );

        $this->createProject = new CreateProjectMock($this);
        $this->logger = new LoggerMock($this);
        $this->sut = new ProjectAddedSubscriber(
            createProject: $this->createProject->getMock(),
            logger: $this->logger->getMock()
        );
    }

    protected function tearDown(): void
    {
        $this->event = null;
        $this->createProject = null;
        $this->logger = null;
        $this->sut = null;
    }

    public function testItHandlesProjectAddedEvent(): void
    {
        $this->createProject->shouldCreateProject($this->event->project());
        $this->logger->shouldLogInfo(
            'ProjectAddedEvent handled.',
            [
                'projectId' => $this->event->project()->id()->value(),
            ]
        );

        $this->sut->__invoke($this->event);
    }

    public function testItLogsErrorWhenProjectCreationFails(): void
    {
        $this->createProject->shouldThrowException($this->event->project());
        $this->logger->shouldLogError(
            'ProjectAddedEvent failed.',
            [
                'projectId' => $this->event->project()->id()->value(),
                'error' => sprintf(
                    "Project with id '%s' already exists.",
                    $this->event->project()->id()->value()
                ),
            ]
        );

        $this->expectException(ProjectAlreadyExistsException::class);
        $this->sut->__invoke($this->event);
    }
}
