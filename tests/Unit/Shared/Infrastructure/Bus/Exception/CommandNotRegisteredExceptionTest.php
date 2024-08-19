<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Bus\Exception;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Infrastructure\Bus\Exception\CommandNotRegisteredException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CommandNotRegisteredExceptionTest extends TestCase
{
    private ?MockObject $command;

    protected function setUp(): void
    {
        $this->command = $this->createMock(Command::class);
    }

    protected function tearDown(): void
    {
        $this->command = null;
    }

    public function testExceptionIsCreated(): void
    {
        $exception = new CommandNotRegisteredException($this->command);
        $commandClass = get_class($this->command);

        $this->assertEquals(
            "Command with class {$commandClass} has no handler registered",
            $exception->getMessage()
        );
    }
}
