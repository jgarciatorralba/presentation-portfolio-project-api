<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\TestCase;

use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use App\UI\Subscriber\ExceptionHttpStatusCodeMapper;

final class ExceptionHttpStatusCodeMapperMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return ExceptionHttpStatusCodeMapper::class;
    }

    public function shouldGetStatusCodeFor(
        string $exceptionClassName,
        ?int $statusCode
    ): void {
        $this->mock
            ->expects($this->once())
            ->method('getStatusCodeFor')
            ->with($exceptionClassName)
            ->willReturn($statusCode);
    }
}
