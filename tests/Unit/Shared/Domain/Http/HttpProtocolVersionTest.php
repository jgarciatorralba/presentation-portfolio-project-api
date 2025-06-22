<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Http;

use App\Shared\Domain\Http\HttpProtocolVersion;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class HttpProtocolVersionTest extends TestCase
{
    public static function tearDownAfterClass(): void
    {
        unset($_SERVER['SERVER_PROTOCOL']);

        parent::tearDownAfterClass();
    }

    #[DataProvider('protocolProvider')]
    public function testItCanBeCreatedFromServerEnvironment(
        string $protocolString,
        HttpProtocolVersion $expected
    ): void {
        $_SERVER['SERVER_PROTOCOL'] = $protocolString;

        $protocol = HttpProtocolVersion::fromServerEnvironment();

        $this->assertInstanceOf(HttpProtocolVersion::class, $protocol);
        $this->assertEquals($expected, $protocol);
    }

    /**
     * @return array<array{string, HttpProtocolVersion}>
     */
    public static function protocolProvider(): array
    {
        return [
            ['HTTP/0.9', HttpProtocolVersion::HTTP_0_9],
            ['HTTP/1.0', HttpProtocolVersion::HTTP_1_0],
            ['HTTP/1.1', HttpProtocolVersion::HTTP_1_1],
            ['HTTP/2.0', HttpProtocolVersion::HTTP_2_0],
            ['HTTP/3.0', HttpProtocolVersion::HTTP_3_0],
            ['Invalid Protocol', HttpProtocolVersion::HTTP_1_1],
        ];
    }

    public function testItHasDefaultValue(): void
    {
        $reflection = new \ReflectionClass(HttpProtocolVersion::class);
        $method = $reflection->getMethod('default');
        $method->setAccessible(true);

        $default = $method->invoke(null);

        $this->assertEquals(HttpProtocolVersion::HTTP_1_1, $default);
    }
}
