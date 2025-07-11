<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Testing;

use App\Shared\Domain\ValueObject\Uuid;
use Faker\Factory;
use Faker\Generator;

final class FakeValueGenerator
{
    private static ?Generator $faker = null;

    private static function generator(): Generator
    {
        return self::$faker ??= Factory::create();
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \OverflowException
     */
    public static function uuid(): Uuid
    {
        return Uuid::fromString(self::generator()->unique()->uuid());
    }

    public static function dateTime(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromMutable(self::generator()->dateTimeBetween());
    }

    public static function string(): string
    {
        return self::generator()->word();
    }

    public static function text(): string
    {
        return self::generator()->text();
    }

    public static function url(): string
    {
        return 'https://' . self::generator()->domainWord() . '.' . self::generator()->tld();
    }

    public static function integer(int $min = 0, ?int $max = null): int
    {
        return self::generator()->numberBetween($min, $max ?? mt_getrandmax());
    }

    public static function float(int $min = 0, ?int $max = null, int $decimals = 2): float
    {
        return self::generator()->randomFloat($decimals, $min, $max);
    }

    public static function boolean(): bool
    {
        return self::generator()->boolean();
    }

    /**
     * @param array<mixed> $options
     */
    public static function randomElement(array $options): mixed
    {
        return self::generator()->randomElement($options);
    }
}
