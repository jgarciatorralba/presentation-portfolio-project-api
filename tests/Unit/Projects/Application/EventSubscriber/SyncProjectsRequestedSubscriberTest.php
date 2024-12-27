<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Application\EventSubscriber;

use App\Projects\Application\EventSubscriber\SyncProjectsRequestedSubscriber;
use App\Projects\Application\Bus\Event\SyncProjectsRequestedEvent;
use App\Projects\Domain\Bus\Event\ProjectAddedEvent;
use App\Projects\Domain\Bus\Event\ProjectModifiedEvent;
use App\Projects\Domain\Bus\Event\ProjectRemovedEvent;
use App\Tests\Builder\Projects\Domain\ProjectBuilder;
use App\Tests\Unit\Projects\TestCase\GetAllProjectsMock;
use App\Tests\Unit\Projects\TestCase\RequestExternalProjectsMock;
use App\Tests\Unit\Shared\TestCase\EventBusMock;
use PHPUnit\Framework\TestCase;

final class SyncProjectsRequestedSubscriberTest extends TestCase
{
    private ?SyncProjectsRequestedEvent $event;
    private ?GetAllProjectsMock $getAllProjects;
    private ?RequestExternalProjectsMock $requestExternalProjects;
    private ?EventBusMock $eventBus;
    private ?SyncProjectsRequestedSubscriber $sut;

    protected function setUp(): void
    {
        $this->event = new SyncProjectsRequestedEvent();

        $this->getAllProjects = new GetAllProjectsMock($this);
        $this->requestExternalProjects = new RequestExternalProjectsMock($this);
        $this->eventBus = new EventBusMock($this);

        $this->sut = new SyncProjectsRequestedSubscriber(
            requestExternalProjects: $this->requestExternalProjects->getMock(),
            getAllProjects: $this->getAllProjects->getMock(),
            eventBus: $this->eventBus->getMock()
        );
    }

    protected function tearDown(): void
    {
        $this->event = null;
        $this->getAllProjects = null;
        $this->requestExternalProjects = null;
        $this->eventBus = null;
        $this->sut = null;
    }

    public function testItPublishesProjectAddedEventWhenProjectIsNotStored(): void
    {
        $externalProjects = ProjectBuilder::buildMany();
        $storedProjects = array_filter(
            $externalProjects,
            fn (int $key): bool => $key !== 0,
            ARRAY_FILTER_USE_KEY
        );

        $this->getAllProjects->shouldGetAllStoredProjects(...$storedProjects);
        $this->requestExternalProjects->shouldRequestExternalProjects(...$externalProjects);

        $this->eventBus->shouldPublishEvents(
            new ProjectAddedEvent($externalProjects[0])
        );

        $result = $this->sut->__invoke($this->event);
        $this->assertNull($result);
    }

    public function testItPublishesProjectRemovedEventWhenProjectIsNotFetched(): void
    {
        $storedProjects = ProjectBuilder::buildMany();
        $externalProjects = array_filter(
            $storedProjects,
            fn (int $key): bool => $key !== 0,
            ARRAY_FILTER_USE_KEY
        );

        $this->getAllProjects->shouldGetAllStoredProjects(...$storedProjects);
        $this->requestExternalProjects->shouldRequestExternalProjects(...$externalProjects);

        $this->eventBus->shouldPublishEvents(
            new ProjectRemovedEvent($storedProjects[0]->id())
        );

        $result = $this->sut->__invoke($this->event);
        $this->assertNull($result);
    }

    public function testItPublishesProjectModifiedEventWhenProjectsAreDifferent(): void
    {
        $storedProject = ProjectBuilder::any()->build();
        $externalProject = ProjectBuilder::any()
            ->withId($storedProject->id())
            ->build();

        $this->getAllProjects->shouldGetAllStoredProjects($storedProject);
        $this->requestExternalProjects->shouldRequestExternalProjects($externalProject);

        $this->eventBus->shouldPublishEvents(
            new ProjectModifiedEvent($externalProject->id())
        );

        $result = $this->sut->__invoke($this->event);
        $this->assertNull($result);
    }
}
