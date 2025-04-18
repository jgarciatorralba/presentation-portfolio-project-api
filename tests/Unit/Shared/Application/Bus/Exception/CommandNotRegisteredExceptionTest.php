<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Application\Bus\Exception;

use App\Shared\Application\Bus\Exception\CommandNotRegisteredException;
use App\Shared\Domain\Bus\Command\Command;
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

    public function testItIsCreated(): void
    {
        $exception = new CommandNotRegisteredException($this->command);
        $commandClass = $this->command instanceof MockObject
            ? $this->command::class
            : self::class;

        $this->assertEquals(
            "Command with class {$commandClass} has no handler registered",
            $exception->getMessage()
        );
    }
}
