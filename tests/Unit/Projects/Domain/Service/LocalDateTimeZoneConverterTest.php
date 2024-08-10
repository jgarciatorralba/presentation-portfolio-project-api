<?php

declare(strict_types=1);

namespace Tests\Unit\Projects\Domain\Service;

use App\Projects\Domain\Service\LocalDateTimeZoneConverter;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LocalDateTimeZoneConverterTest extends TestCase
{
    private ?LocalDateTimeZoneConverter $localDateTimeZoneConverter = null;

    protected function setUp(): void
    {
        $this->localDateTimeZoneConverter = new LocalDateTimeZoneConverter();
    }

    protected function tearDown(): void
    {
        $this->localDateTimeZoneConverter = null;
    }

    #[DataProvider('datesToConvert')]
    public function testConvert(
        string $receivedDateTimeString,
        string $localDateTimeString
    ): void {
        $originalDateTime = new DateTimeImmutable($receivedDateTimeString);
        $convertedDateTime = $this->localDateTimeZoneConverter->convert($originalDateTime);
        $convertedDateTimeFormatted = $convertedDateTime->format('Y-m-d H:i:s');

        $this->assertEquals(
            date_default_timezone_get(),
            $convertedDateTime->getTimezone()->getName()
        );
        $this->assertEquals($localDateTimeString, $convertedDateTimeFormatted);
    }

    /**
     * @return array<string, string[]>
     */
    public static function datesToConvert(): array
    {
        return [
            'summertime dateTime' => ['2021-06-24T12:00:00Z', '2021-06-24 14:00:00'],
            'wintertime dateTime' => ['2020-12-05T18:30:00+01:00', '2020-12-05 18:30:00']
        ];
    }
}
