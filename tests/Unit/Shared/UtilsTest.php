<?php

declare(strict_types=1);

namespace Tests\Unit\Shared;

use App\Shared\Utils;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class UtilsTest extends TestCase
{
    public function testItConvertsDateToUTCString(): void
    {
        $date = new \DateTimeImmutable();
        $dateToUTCString = Utils::dateToUTCString($date);

        $this->assertIsString($dateToUTCString);
        $this->assertMatchesRegularExpression(
            '/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])T([01]\d|2[0-3]):[0-5]\d:[0-5]\d\.\d{3}Z$/',
            $dateToUTCString
        );
    }

    #[DataProvider('dataStringToDate')]
    public function testItConvertsStringToDate(
        string $stringToConvert,
        \DateTimeInterface $expectedResult
    ): void {
        $date = Utils::stringToDate($stringToConvert);

        $this->assertInstanceOf(\DateTimeInterface::class, $date);
        $this->assertGreaterThanOrEqual($expectedResult->getTimestamp(), $date->getTimestamp());
    }

    public function testItExtractsClassName(): void
    {
        $className = Utils::extractClassName(Utils::class);
        $this->assertEquals('Utils', $className);
    }

    #[DataProvider('dataToSnakeCase')]
    public function testItConvertsToSnakeCase(
        string $stringToConvert,
        string $expectedResult
    ): void {
        $snakeCase = Utils::toSnakeCase($stringToConvert);
        $this->assertEquals($expectedResult, $snakeCase);
    }

    /** @return array<string, array<string|DateTimeInterface>> */
    public static function dataStringToDate(): array
    {
        return [
            'valid datetime string' => ['2010-10-10', new \DateTimeImmutable('2010-10-10')],
            'invalid datetime string' => ['invalid-date-string', new \DateTimeImmutable()]
        ];
    }

    /** @return array<string, string[]> */
    public static function dataToSnakeCase(): array
    {
        return [
            'camel case string' => ['camelCaseString', 'camel_case_string'],
            'pascal case string' => ['PascalCaseString', 'pascal_case_string']
        ];
    }
}
