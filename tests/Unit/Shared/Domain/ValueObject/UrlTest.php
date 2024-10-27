<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\Url;
use App\Shared\Utils;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    #[DataProvider('dataIsCreated')]
    public function testItIsCreatedFromString(
        string $urlValue,
        bool $expectException
    ): void {
        if ($expectException) {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage(
                sprintf(
                    "'%s' does not allow the value '%s'.",
                    Utils::extractClassName(Url::class),
                    $urlValue
                )
            );
        }

        $url = Url::fromString($urlValue);
        $this->assertSame($urlValue, $url->value());
    }

    /**
     * @return array<string, array<string, bool>>
     */
    public static function dataIsCreated(): array
    {
        return [
            'valid url' => ['https://example.com', false],
            'invalid url' => ['invalid-url', true]
        ];
    }

    public function testItIsStringable(): void
    {
        $url = Url::fromString('https://example.com');

        $this->assertSame($url->value(), (string) $url);
    }
}
