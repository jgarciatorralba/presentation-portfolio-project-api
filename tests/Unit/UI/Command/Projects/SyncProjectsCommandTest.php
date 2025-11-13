<?php

declare(strict_types=1);

namespace Tests\Unit\UI\Command\Projects;

use App\Projects\Application\Bus\Event\SyncProjectsRequestedEvent;
use Tests\Unit\UI\TestCase\EventBusMock;
use Tests\Unit\UI\TestCase\LockFactoryMock;
use Tests\Unit\UI\TestCase\LockMock;
use App\UI\Command\Projects\SyncProjectsCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class SyncProjectsCommandTest extends TestCase
{
    private ?EventBusMock $eventBusMock;
    private ?LockFactoryMock $lockFactoryMock;
    private ?LockMock $lockMock;
    private ?SyncProjectsCommand $sut;
    private ?CommandTester $commandTester;

    protected function setUp(): void
    {
        $this->eventBusMock = new EventBusMock($this);
        $this->lockFactoryMock = new LockFactoryMock($this);
        $this->lockMock = new LockMock($this);

        $this->sut = new SyncProjectsCommand(
            eventBus: $this->eventBusMock->getMock(),
            lockFactory: $this->lockFactoryMock->getMock(),
        );

        $this->commandTester = new CommandTester($this->sut);
    }

    protected function tearDown(): void
    {
        $this->eventBusMock = null;
        $this->lockFactoryMock = null;
        $this->lockMock = null;
        $this->sut = null;
        $this->commandTester = null;
    }

    public function testItIsExecutedSuccessfully(): void
    {
        $this->lockMock
            ->shouldAcquireWithResult(true);

        $this->lockFactoryMock
            ->shouldCreateLock($this->lockMock->getMock());

        $this->eventBusMock
            ->shouldPublishEvents(SyncProjectsRequestedEvent::eventType());

        $this->commandTester->execute(input: []);

        $output = $this->commandTester->getDisplay();
        $uuidPattern = '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}';
        $dateTimePattern = '\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])T([01]\d|2[0-3]):[0-5]\d:[0-5]\d\.\d{3}Z';
        $pattern = "/\s*\[OK\] Event $uuidPattern published successfully on \s*$dateTimePattern\s*/";

        $this->assertMatchesRegularExpression($pattern, $output);
    }

    public function testItOutputsAWarningIfItIsAlreadyBeingExecuted(): void
    {
        $this->lockMock
            ->shouldAcquireWithResult(false);

        $this->lockFactoryMock
            ->shouldCreateLock($this->lockMock->getMock());

        $this->commandTester->execute(input: []);

        $output = $this->commandTester->getDisplay();
        $pattern = "/\s*\[WARNING\] The command is already running in another process.\s*/";

        $this->assertMatchesRegularExpression($pattern, $output);
    }
}
