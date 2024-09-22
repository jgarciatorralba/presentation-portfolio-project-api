<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Command\Projects;

use App\Projects\Application\Bus\Event\SyncProjectsRequestedEvent;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Tests\Unit\UI\TestCase\EventBusMock;
use App\UI\Command\Projects\SyncProjectsCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class SyncProjectsCommandTest extends TestCase
{
    private ?EventBusMock $eventBusMock;
    private ?SyncProjectsCommand $sut;
    private ?CommandTester $commandTester;

    protected function setUp(): void
    {
        $this->eventBusMock = new EventBusMock();
        $this->sut = new SyncProjectsCommand(
            eventBus: $this->eventBusMock->getMock()
        );
        $this->commandTester = new CommandTester($this->sut);
    }

    protected function tearDown(): void
    {
        $this->eventBusMock = null;
        $this->sut = null;
        $this->commandTester = null;
    }

    public function testItIsExecutedSuccessfully(): void
    {
        $this->eventBusMock
            ->shouldPublishEvents(SyncProjectsRequestedEvent::eventType());

        $this->commandTester->execute(input: []);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Event published successfully!', $output);
    }
}
