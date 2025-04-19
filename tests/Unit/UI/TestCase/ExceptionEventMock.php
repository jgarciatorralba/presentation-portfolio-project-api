<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class ExceptionEventMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return ExceptionEvent::class;
    }

    public function shouldBeMainRequest(bool $isMainRequest): void
    {
        $this->mock
            ->expects($this->once())
            ->method('isMainRequest')
            ->willReturn($isMainRequest);
    }

    public function shouldCallSetResponse(int $times): void
    {
        $this->mock
            ->expects($this->exactly($times))
            ->method('setResponse');
    }

    public function shouldGetThrowable(\Throwable $exception): void
    {
        $this->mock
            ->expects($this->once())
            ->method('getThrowable')
            ->willReturn($exception);
    }

    public function shouldGetStatusCode(?int $statusCode): void
    {
        $this->mock
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn($statusCode ?? null);
    }
}
