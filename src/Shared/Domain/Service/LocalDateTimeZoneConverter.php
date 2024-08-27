<?php

declare(strict_types=1);

namespace App\Shared\Domain\Service;

final class LocalDateTimeZoneConverter
{
    private string $localTimeZone;

    public function __construct()
    {
        $this->localTimeZone = date_default_timezone_get();
    }

    public function convert(\DateTimeImmutable $dateTime): \DateTimeImmutable
    {
        return $dateTime->setTimezone(new \DateTimeZone($this->localTimeZone));
    }
}
