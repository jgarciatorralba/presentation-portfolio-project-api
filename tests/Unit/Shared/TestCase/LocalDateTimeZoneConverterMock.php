<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Shared\Domain\Service\LocalDateTimeZoneConverter;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\IncompatibleReturnValueException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;

final class LocalDateTimeZoneConverterMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return LocalDateTimeZoneConverter::class;
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
    public function shouldConvert(
        \DateTimeImmutable $dateTime,
        \DateTimeImmutable $convertedDateTime
    ): void {
        $this->mock
            ->expects($this->once())
            ->method('convert')
            ->with($dateTime)
            ->willReturn($convertedDateTime);
    }
}
