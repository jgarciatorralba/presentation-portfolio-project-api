<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\TestCase;

use App\Shared\Domain\Service\LocalDateTimeZoneConverter;
use App\Tests\Unit\Shared\Infrastructure\Testing\AbstractMock;

final class LocalDateTimeZoneConverterMock extends AbstractMock
{
    protected function getClassName(): string
    {
        return LocalDateTimeZoneConverter::class;
    }

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
