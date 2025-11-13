<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Utils;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UuidTest extends TestCase
{
    #[DataProvider('dataIsCreated')]
    public function testItIsCreated(string $uuidValue, bool $expectException): void
    {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage(
                sprintf(
                    "'%s' does not allow the value '%s'.",
                    Utils::extractClassName(Uuid::class),
                    $uuidValue
                )
            );
        }

        $uuid = new Uuid($uuidValue);
        $this->assertSame($uuidValue, $uuid->value());
    }

    /**
     * @return array<string, array<string, bool>>
     */
    public static function dataIsCreated(): array
    {
        return [
            'valid uuid' => [Uuid::random()->value(), false],
            'invalid uuid' => ['invalid-uuid', true]
        ];
    }

    public function testItIsCreatedFromString(): void
    {
        $uuid = Uuid::random();
        $uuidFromString = Uuid::fromString($uuid->value());

        $this->assertSame($uuid->value(), $uuidFromString->value());
    }

    public function testItIsCreatedRandom(): void
    {
        $uuid = Uuid::random();

        $this->assertInstanceOf(Uuid::class, $uuid);
        $this->assertIsString($uuid->value());
        $this->assertNotSame($uuid->value(), Uuid::random()->value());
    }

    public function testItConvertsToString(): void
    {
        $uuid = Uuid::random();
        $this->assertSame($uuid->value(), (string) $uuid);
    }

    public function testItIsComparable(): void
    {
        $uuid = Uuid::random();
        $this->assertTrue($uuid->equals($uuid));
        $this->assertFalse($uuid->equals(Uuid::random()));
    }
}
