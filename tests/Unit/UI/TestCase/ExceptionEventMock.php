<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\IncompatibleReturnValueException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class ExceptionEventMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return ExceptionEvent::class;
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
    public function shouldBeMainRequest(bool $isMainRequest): void
    {
        $this->mock
            ->expects($this->once())
            ->method('isMainRequest')
            ->willReturn($isMainRequest);
    }

    /**
     * @throws InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     */
    public function shouldCallSetResponse(int $times): void
    {
        $this->mock
            ->expects($this->exactly($times))
            ->method('setResponse');
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
    public function shouldGetThrowable(\Throwable $exception): void
    {
        $this->mock
            ->expects($this->once())
            ->method('getThrowable')
            ->willReturn($exception);
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
    public function shouldGetStatusCode(?int $statusCode): void
    {
        $this->mock
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn($statusCode ?? null);
    }
}
