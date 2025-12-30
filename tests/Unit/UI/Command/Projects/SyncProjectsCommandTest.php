<?php

declare(strict_types=1);

namespace Tests\Unit\UI\Command\Projects;

use App\Projects\Application\Bus\Event\SyncProjectsRequestedEvent;
use Tests\Unit\UI\TestCase\EventBusMock;
use Tests\Unit\UI\TestCase\LockFactoryMock;
use Tests\Unit\UI\TestCase\LockMock;
use App\Shared\Domain\Bus\Event\EventBus;
use App\UI\Command\Projects\SyncProjectsCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class SyncProjectsCommandTest extends TestCase
{
    private ?LockFactoryMock $lockFactoryMock;
    private ?LockMock $lockMock;

    protected function setUp(): void
    {
        $this->lockFactoryMock = new LockFactoryMock($this);
        $this->lockMock = new LockMock($this);
    }

    protected function tearDown(): void
    {
        $this->lockFactoryMock = null;
        $this->lockMock = null;
    }

    public function testItIsExecutedSuccessfully(): void
    {
        $this->lockMock
            ->shouldAcquireWithResult(true);

        $this->lockFactoryMock
            ->shouldCreateLock($this->lockMock->getMock());

        $eventBusMock = new EventBusMock($this);
        $eventBusMock
            ->shouldPublishEvents(SyncProjectsRequestedEvent::eventType());

        $sut = new SyncProjectsCommand(
            eventBus: $eventBusMock->getMock(),
            lockFactory: $this->lockFactoryMock->getMock(),
        );

        $commandTester = new CommandTester($sut);
        $commandTester->execute(input: []);

        $output = $commandTester->getDisplay();
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

        $sut = new SyncProjectsCommand(
            eventBus: $this->createStub(EventBus::class),
            lockFactory: $this->lockFactoryMock->getMock(),
        );

        $commandTester = new CommandTester($sut);
        $commandTester->execute(input: []);

        $output = $commandTester->getDisplay();
        $pattern = "/\s*\[WARNING\] The command is already running in another process.\s*/";

        $this->assertMatchesRegularExpression($pattern, $output);
    }
}
