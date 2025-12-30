<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Application\Bus\Exception;

use App\Shared\Application\Bus\Exception\CommandNotRegisteredException;
use App\Shared\Domain\Bus\Command\Command;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

final class CommandNotRegisteredExceptionTest extends TestCase
{
    private Stub&Command $command;

    protected function setUp(): void
    {
        $this->command = $this->createStub(Command::class);
    }

    public function testItIsCreated(): void
    {
        $exception = new CommandNotRegisteredException($this->command);
        $commandClass = $this->command instanceof Stub
            ? $this->command::class
            : self::class;

        $this->assertEquals(
            "Command with class {$commandClass} has no handler registered",
            $exception->getMessage()
        );
    }
}
