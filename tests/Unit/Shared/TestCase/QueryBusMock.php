<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Shared\Domain\Bus\Query\Query;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\IncompatibleReturnValueException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class QueryBusMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return MessageBusInterface::class;
    }

    /**
     * @throws Exception
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     */
    public function shouldDispatchQuery(Query $query, HandledStamp $stamp): void
    {
        $envelope = new Envelope($query, [$stamp]);

        $this->mock
            ->expects($this->once())
            ->method('dispatch')
            ->with($query)
            ->willReturn($envelope);
    }

    /**
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     */
    public function willThrowException(\Throwable $exception): void
    {
        $this->mock
            ->expects($this->once())
            ->method('dispatch')
            ->willThrowException($exception);
    }
}
