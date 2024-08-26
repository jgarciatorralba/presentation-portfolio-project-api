<?php

declare(strict_types=1);

namespace App\Tests\Unit\Projects\Domain\Service;

use App\Projects\Domain\Service\LocalDateTimeZoneConverter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LocalDateTimeZoneConverterTest extends TestCase
{
    private ?LocalDateTimeZoneConverter $sut = null;

    protected function setUp(): void
    {
        $this->sut = new LocalDateTimeZoneConverter();
    }

    protected function tearDown(): void
    {
        $this->sut = null;
    }

    #[DataProvider('datesToConvert')]
    public function testConvert(
        string $receivedDateTimeString,
        string $localDateTimeString
    ): void {
        $originalDateTime = new \DateTimeImmutable($receivedDateTimeString);
        $convertedDateTime = $this->sut->convert($originalDateTime);
        $formattedDateTime = $convertedDateTime->format('Y-m-d H:i:s');

        $this->assertEquals(
            date_default_timezone_get(),
            $convertedDateTime->getTimezone()->getName()
        );
        $this->assertEquals($localDateTimeString, $formattedDateTime);
    }

    /**
     * @return array<string, string[]>
     */
    public static function datesToConvert(): array
    {
        return [
            'summertime DateTime' => ['2021-06-24T12:00:00Z', '2021-06-24 14:00:00'],
            'wintertime DateTime' => ['2020-12-05T18:30:00+01:00', '2020-12-05 18:30:00']
        ];
    }
}
