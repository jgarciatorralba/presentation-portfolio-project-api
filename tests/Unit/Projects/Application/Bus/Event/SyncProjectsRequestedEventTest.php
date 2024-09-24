<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Application\Bus\Event;

use App\Projects\Application\Bus\Event\SyncProjectsRequestedEvent;
use App\Shared\Application\Bus\Event\ApplicationEvent;
use App\Shared\Domain\Bus\Event\Event;
use PHPUnit\Framework\TestCase;

final class SyncProjectsRequestedEventTest extends TestCase
{
    private ?SyncProjectsRequestedEvent $syncProjectsRequestedEvent;

    protected function setUp(): void
    {
        $this->syncProjectsRequestedEvent = new SyncProjectsRequestedEvent();
    }

    protected function tearDown(): void
    {
        $this->syncProjectsRequestedEvent = null;
    }

    public function testItShouldBeAnInstanceOfApplicationEvent(): void
    {
        $this->assertInstanceOf(
            ApplicationEvent::class,
            $this->syncProjectsRequestedEvent
        );
    }

    public function testItShouldBeAnInstanceOfEvent(): void
    {
        $this->assertInstanceOf(
            Event::class,
            $this->syncProjectsRequestedEvent
        );

        $this->assertNotEmpty(
            $this->syncProjectsRequestedEvent->eventId()
        );

        $this->assertNotEmpty(
            $this->syncProjectsRequestedEvent->occurredOn()
        );
    }

    public function testItShouldHaveAnEventType(): void
    {
        $this->assertEquals(
            'projects.application.sync_projects_requested',
            $this->syncProjectsRequestedEvent::eventType()
        );
    }

    public function testItShouldHaveAnArrayRepresentation(): void
    {
        $arrayRepresentation = $this->syncProjectsRequestedEvent->toArray();

        $this->assertIsArray($arrayRepresentation);
        $this->assertArrayHasKey('id', $arrayRepresentation);
        $this->assertArrayHasKey('eventType', $arrayRepresentation);
        $this->assertArrayHasKey('occurredOn', $arrayRepresentation);
        $this->assertArrayHasKey('attributes', $arrayRepresentation);
    }
}
